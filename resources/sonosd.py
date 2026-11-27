from __future__ import annotations

import warnings
import asyncio
import logging

from soco import SoCo, discover, events_asyncio
from soco.core import MUSIC_SRC_LINE_IN, MUSIC_SRC_RADIO
import soco.config as soco_config
from soco.data_structures import SearchResult
from soco.exceptions import SoCoUPnPException
from requests.exceptions import RequestException

from jeedomdaemon.base_daemon import BaseDaemon
from jeedomdaemon.base_config import BaseConfig

from sonos.const import RAMP_TYPES, SONOS_STATE_PLAYING
from sonos.data import SonosData
from sonos.speaker import SonosSpeaker

TTS_MAX_WAIT_LOOPS = 1200


class SonosConfig(BaseConfig):
    def __init__(self):
        super().__init__()

        self.add_argument("--networksToScan", type=str, default=None)

    @property
    def networks_to_scan(self) -> list[str] | None:
        if self._args.networksToScan is None or self._args.networksToScan == '':
            return None
        return [self._args.networksToScan]


class SonosDaemon(BaseDaemon):
    def __init__(self) -> None:
        self._config = SonosConfig()
        super().__init__(self._config, self._on_start, self._on_message, self._on_stop)

        soco_config.EVENTS_MODULE = events_asyncio

        logging.getLogger('aiohttp').setLevel(logging.WARNING)
        warnings.filterwarnings("ignore", message="The output type of this method will probably change in the future to use SoCo data structures")
        warnings.filterwarnings("ignore", message="Call to deprecated function")

        self.__update_task: asyncio.Task | None = None
        self.__sync_task: asyncio.Task | None = None

        self._speakers: dict[str, SonosSpeaker] = {}
        self._sonos_data = SonosData()

        self._favorites: list[dict] = []
        self._radios: SearchResult = []

    async def _on_start(self):
        await self._discover_and_sync()
        self.__sync_task = asyncio.create_task(self._auto_sync())
        self.__update_task = asyncio.create_task(self._auto_update())

    async def _on_message(self, message: list):
        if 'action' not in message:
            self._logger.error('No action in message: %s', message)
            return

        if 'uid' in message:
            if message['uid'] not in self._speakers:
                self._logger.warning('No speaker with uid: %s', message['uid'])
                return

            speaker = self._speakers[message['uid']]
            coordinator = speaker if speaker.is_coordinator else speaker.coordinator

            if message['action'] == 'play_favorite':
                await self._play_favorite(speaker, coordinator, message['title'])
            elif message['action'] == 'tts':
                await self._play_tts(speaker, coordinator, message)
            else:
                await asyncio.to_thread(self._execute_speaker_action, speaker, coordinator, message)
            return
        if message['action'] == 'sync':
            await self._discover_and_sync()
        else:
            self._logger.error('Unknown action: %s', message['action'])

    def _execute_speaker_action(self, speaker: SonosSpeaker, coordinator: SonosSpeaker, message: dict):
        action = message['action']
        # actions on speaker: volume, equalizer, speaker controle
        if action == 'mute':
            speaker.soco.mute = True
        elif action == 'unmute':
            speaker.soco.mute = False
        elif action == 'volume':
            speaker.soco.volume = message['slider']
        elif action == 'inc_volume':
            speaker.soco.set_relative_volume(1)
        elif action == 'dec_volume':
            speaker.soco.set_relative_volume(-1)
        elif action == 'ramp_to_volume':
            try:
                ramp_type = RAMP_TYPES[message['title']]
            except KeyError:
                self._logger.error("Incorrect ramp type %s", message['title'])
                return
            speaker.soco.ramp_to_volume(message['message'], ramp_type)
        elif action == 'switch_to_line_in':
            speaker.soco.switch_to_line_in()
        elif action == 'switch_to_tv':
            speaker.soco.switch_to_tv()
        elif action == 'led_on':
            speaker.set_status_light(True)
        elif action == 'led_off':
            speaker.set_status_light(False)
        elif action == 'loudness_on':
            speaker.soco.loudness = True
        elif action == 'loudness_off':
            speaker.soco.loudness = False
        elif action == 'buttons_on':
            speaker.set_buttons_enabled(True)
        elif action == 'buttons_off':
            speaker.set_buttons_enabled(False)
        elif action == 'balance':
            speaker.set_balance(int(message['slider']))
        elif action == 'bass':
            speaker.soco.bass = message['slider']
        elif action == 'treble':
            speaker.soco.treble = message['slider']

        # actions to manage groups
        elif action == 'join':
            master = next((item for item in self._speakers.values() if item.zone_name == message['title']), None)
            if master is None:
                self._logger.warning("No zone '%s'", message['title'])
            else:
                master = master if master.is_coordinator else master.coordinator
                speaker.soco.join(master.soco)
        elif action == 'unjoin':
            speaker.soco.unjoin()
        elif action == 'partymode':
            speaker.soco.partymode()

        # action on playlist & playmode
        elif action == 'repeat':
            coordinator.soco.repeat = not coordinator.soco.repeat
        elif action == 'repeat_on':
            coordinator.soco.repeat = True
        elif action == 'repeat_off':
            coordinator.soco.repeat = False
        elif action == 'shuffle':
            coordinator.soco.shuffle = not coordinator.soco.shuffle
        elif action == 'shuffle_on':
            coordinator.soco.shuffle = True
        elif action == 'shuffle_off':
            coordinator.soco.shuffle = False
        elif action == 'play_mode':
            coordinator.soco.play_mode = message['select']
        elif action == 'play':
            self._logger.info("play on %s", coordinator.zone_name)
            coordinator.soco.play()
        elif action == 'pause':
            self._logger.info("pause on %s", coordinator.zone_name)
            coordinator.soco.pause()
        elif action == 'stop':
            self._logger.info("stop on %s", coordinator.zone_name)
            coordinator.soco.stop()
        elif action == 'previous':
            self._logger.info("previous on %s", coordinator.zone_name)
            coordinator.soco.previous()
        elif action == 'next':
            self._logger.info("next on %s", coordinator.zone_name)
            coordinator.soco.next()
        elif action == 'cross_fade_on':
            coordinator.soco.cross_fade = True
        elif action == 'cross_fade_off':
            coordinator.soco.cross_fade = False

        elif action == 'play_playlist':
            playlist = coordinator.soco.get_sonos_playlist_by_attr('title', message['title'])
            coordinator.soco.clear_queue()
            coordinator.soco.add_to_queue(playlist)
            self._logger.info("playing playlist %s in %s", playlist.title, coordinator.zone_name)
            coordinator.soco.play_from_queue(0)
        elif action == 'play_radio':
            radio = next((r for r in self._radios if r.title == message['title']), None)
            if radio is None:
                self._logger.error("Radio '%s' not found, cannot play on %s", message['title'], speaker.zone_name)
            else:
                coordinator.soco.clear_queue()
                coordinator.soco.add_to_queue(radio)
                self._logger.info("playing radio %s in %s", radio.title, coordinator.zone_name)
                coordinator.soco.play_from_queue(0)
        elif action == 'play_mp3radio':
            _title = message['title'] if message['title'] else 'MP3 Radio'
            coordinator.soco.play_uri(f"x-rincon-mp3radio://{message['message']}", title=_title)

        else:
            self._logger.error("Unknown action '%s' for speaker %s", action, message['uid'])

    async def _play_favorite(self, speaker: SonosSpeaker, coordinator: SonosSpeaker, title: str):
        favorites = getattr(self._sonos_data, 'favorites', [])
        fav = next((f for f in favorites if f.title == title), None)
        if fav is not None:
            self._logger.info("playing favorite %s in %s", title, coordinator.zone_name)
            try:
                await asyncio.to_thread(self._play_favorite_from_music_library, coordinator, fav)
                return
            except SoCoUPnPException as e:
                self._logger.warning("Favorite '%s' cannot be played from music library on %s: %s", title, coordinator.zone_name, e)
            except RequestException as e:
                self._logger.warning("No answer from %s while playing favorite '%s': %s", coordinator.zone_name, title, e)
                return

        fav = next((f for f in self._favorites if f['title'] == title), None)
        if fav is None:
            self._logger.error("Favorite '%s' not found, cannot play on %s", title, speaker.zone_name)
            return
        self._logger.info("playing favorite %s from controler in %s", title, coordinator.zone_name)
        try:
            await asyncio.to_thread(self._play_favorite_from_controler, coordinator, fav)
        except SoCoUPnPException as e:
            self._logger.warning("Favorite '%s' cannot be played from music controler on %s: %s", title, coordinator.zone_name, e)
        except RequestException as e:
            self._logger.warning("No answer from %s while playing favorite '%s': %s", coordinator.zone_name, title, e)

    async def _play_tts(self, speaker: SonosSpeaker, coordinator: SonosSpeaker, message: dict):
        def _start():
            coordinator.snapshot(False)
            coordinator.soco.play_mode = 'NORMAL'
            speaker.soco.mute = False
            try:
                speaker.soco.volume = int(message['title'])
            except ValueError:
                pass
            coordinator.soco.play_uri(f"x-file-cifs:{message['file']}", '', 'text-to-speech')

        await asyncio.to_thread(_start)
        await asyncio.sleep(1)
        for _ in range(TTS_MAX_WAIT_LOOPS):
            if coordinator.media.playback_status != SONOS_STATE_PLAYING:
                break
            await asyncio.sleep(0.5)
        await asyncio.sleep(0.5)
        await asyncio.to_thread(coordinator.restore)

    async def _on_stop(self):
        for task in (self.__update_task, self.__sync_task):
            if task is not None:
                task.cancel()
        await asyncio.gather(
            *(speaker.async_unsubscribe() for speaker in self._speakers.values()),
            return_exceptions=True
        )
        if events_asyncio.event_listener is not None:
            await events_asyncio.event_listener.async_stop()

    def _play_favorite_from_music_library(self, coordinator: SonosSpeaker, fav):
        uri = fav.reference.get_uri()
        if SoCo.music_source_from_uri(uri) in (MUSIC_SRC_RADIO, MUSIC_SRC_LINE_IN):
            coordinator.soco.play_uri(uri, meta=fav.resource_meta_data, title=fav.title)
            return

        coordinator.soco.clear_queue()
        coordinator.soco.add_to_queue(fav)
        coordinator.soco.play_from_queue(0)

    def _play_favorite_from_controler(self, coordinator: SonosSpeaker, fav: dict):
        if SoCo.music_source_from_uri(fav['uri']) in (MUSIC_SRC_RADIO, MUSIC_SRC_LINE_IN):
            coordinator.soco.play_uri(fav['uri'], meta=fav['meta'] or '', title=fav['title'])
            return

        coordinator.soco.clear_queue()
        coordinator.soco.avTransport.AddURIToQueue(
            [
                ("InstanceID", 0),
                ("EnqueuedURI", fav['uri']),
                ("EnqueuedURIMetaData", fav['meta']),
                ("DesiredFirstTrackNumberEnqueued", 0),
                ("EnqueueAsNext", 0),
            ]
        )
        coordinator.soco.play_from_queue(0)

    async def _auto_sync(self):
        while True:
            try:
                await asyncio.sleep(3600)
                await self._discover_and_sync()
            except asyncio.CancelledError:
                break
            except Exception as e:
                self._logger.warning("Exception during auto sync: %s", e)
        self._logger.info("Auto sync cancelled")

    async def _auto_update(self):
        try:
            self._logger.info("Start auto update")
            while True:
                for s in list(self._speakers.values()):
                    if not s.available:
                        continue
                    try:
                        await asyncio.to_thread(s.poll_status_light_and_buttons)
                    except Exception as e:
                        self._logger.debug("Polling %s failed: %s", s.zone_name, e)
                await asyncio.sleep(60)
        except asyncio.CancelledError:
            self._logger.info("stop auto update")

    async def _discover_and_sync(self):
        await self.__discover_controllers()

        try:
            random_speaker = next(s for s in self._speakers.values() if s.available)
            coordinator = random_speaker if random_speaker.is_coordinator else random_speaker.coordinator
            self._logger.info(f"Use {coordinator.zone_name} to get favorites, playlists & radios")
            await self.__get_favorites(coordinator)
            await self.__get_playlists(coordinator)
            await self.__get_radios(coordinator)
            await self.__get_alarms(coordinator)
        except StopIteration:
            self._logger.warning("No speakers available to get favorites, playlists & radios")

    async def __discover_controllers(self):
        if self._config.networks_to_scan is not None:
            self._logger.info("Network to scan: %s", self._config.networks_to_scan)
        discovered_soco = await asyncio.to_thread(
            discover, timeout=10, allow_network_scan=True, networks_to_scan=self._config.networks_to_scan
        )
        if discovered_soco is None:
            self._logger.warning("No Sonos discovered, do you have Sonos speaker on the same network?")
            return

        socos: list[SoCo] = list(discovered_soco)
        self._logger.info(f"Discovered {len(socos)} speakers:")
        for soco in socos:
            if soco.uid in self._speakers:
                speaker = self._speakers[soco.uid]
                self._logger.info(f" - {speaker.zone_name} (already known)")
                if not speaker.available:
                    self._logger.info(f"Speaker {speaker.zone_name} was unavailable, resubscribing")
                    await speaker.async_subscribe()
                continue

            try:
                new_speaker = await asyncio.to_thread(SonosSpeaker, self._sonos_data, soco, self.__on_speaker_change)
            except Exception as e:
                self._logger.warning("Cannot initialize speaker %s: %s", soco.ip_address, e)
                continue
            self._logger.info(f" - {new_speaker.zone_name}")
            self._sonos_data.discovered[soco.uid] = new_speaker
            self._speakers[soco.uid] = new_speaker
            await self.add_change(f'controllers::{new_speaker.uid}', new_speaker.get_info())
            await new_speaker.async_subscribe()

        discovered_uids = {soco.uid for soco in socos}
        for speaker in self._speakers.values():
            if speaker.uid not in discovered_uids and speaker.available:
                self._logger.info(f"Speaker {speaker.zone_name} is no more available")

    async def __get_favorites(self, speaker: SonosSpeaker):
        result = await asyncio.to_thread(speaker.soco.get_sonos_favorites)
        self._logger.info("get %s favorites out of %s", result['returned'], result['total'])
        self._logger.debug('favorites: %s', result['favorites'])
        self._favorites = result['favorites']

        results = await asyncio.to_thread(speaker.soco.music_library.get_sonos_favorites)
        self._logger.info("get %s favorites out of %s", results.number_returned, results.total_matches)

        # self._logger.debug('favorites: %s', result['favorites'])
        self._sonos_data.favorites = results
        await self.add_change('favorites', list({r.title for r in results}))

    async def __get_playlists(self, speaker: SonosSpeaker):
        results = await asyncio.to_thread(speaker.soco.get_sonos_playlists)
        self._logger.info("get %s playlists out of %s", results.number_returned, results.total_matches)
        await self.add_change('playlists', list({r.title for r in results}))

    async def __get_radios(self, speaker: SonosSpeaker):
        self._radios = await asyncio.to_thread(speaker.soco.music_library.get_favorite_radio_stations)
        self._logger.info("get %s radios out of %s", self._radios.number_returned, self._radios.total_matches)
        await self.add_change('radios', list({r.title for r in self._radios}))

    async def __get_alarms(self, speaker: SonosSpeaker):
        await asyncio.to_thread(self._sonos_data.alarms.update, speaker.soco)

    async def __send_speaker(self, speaker: SonosSpeaker):
        await self.add_change(f'speakers::{speaker.uid}', speaker.to_dict())

    def __on_speaker_change(self, speaker: SonosSpeaker):
        self._loop.call_soon_threadsafe(self.__schedule_send_speaker, speaker)

    def __schedule_send_speaker(self, speaker: SonosSpeaker):
        self._loop.create_task(self.__send_speaker(speaker))
        for s in speaker.sonos_group:
            if s is not speaker:
                self._loop.create_task(self.__send_speaker(s))


SonosDaemon().run()
