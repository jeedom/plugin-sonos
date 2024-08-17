"""Base class for common speaker tasks."""

from __future__ import annotations

import contextlib
import datetime
import asyncio
from collections.abc import Callable
import logging
import time
from typing import Any, Coroutine

import defusedxml.ElementTree as ET
from soco import SoCoException
from soco.core import SoCo
from soco.events_base import Event as SonosEvent, SubscriptionBase
from soco.snapshot import Snapshot
from soco.exceptions import SoCoSlaveException
# from sonos_websocket import SonosWebsocket

from .alarms import SonosAlarms
from .const import (
    ALL_FEATURES,
    SONOS_STATE_TRANSITIONING,
    SUBSCRIPTION_TIMEOUT,
)
from .data import SonosData
from .exception import S1BatteryMissing, SonosSubscriptionsFailed, SonosUpdateError
from .media import SonosMedia

NEVER_TIME = -1200.0
RESUB_COOLDOWN_SECONDS = 10.0
EVENT_CHARGING = {
    "CHARGING": True,
    "NOT_CHARGING": False,
}
SUBSCRIPTION_SERVICES = {
    # "alarmClock",
    "avTransport",
    "contentDirectory",
    "deviceProperties",
    "renderingControl",
    "zoneGroupTopology",
}
SUPPORTED_VANISH_REASONS = ("powered off", "sleeping", "switch to bluetooth", "upgrade")
UNUSED_DEVICE_KEYS = ["SPID", "TargetRoomName"]


_LOGGER = logging.getLogger(__name__)

class SonosSpeaker:
    """Representation of a Sonos speaker."""

    def __init__(
        self,
        data: SonosData,
        soco: SoCo,
        change_cb: Callable[[SonosSpeaker]],
        # zone_group_state_sub: SubscriptionBase = None,
    ) -> None:
        """Initialize a SonosSpeaker."""
        self.data = data
        self.soco = soco
        # self.websocket: SonosWebsocket | None = None
        self.household_id: str = soco.household_id
        self.media = SonosMedia(soco)
        self.available: bool = True
        self.__change_cb = change_cb

        # Device information
        speaker_info = soco.get_speaker_info(True, timeout=7)
        self.ip_address: str = soco.ip_address
        self.hardware_version: str = speaker_info["hardware_version"]
        self.software_version: str = speaker_info["software_version"]
        self.mac_address: str = speaker_info["mac_address"]
        self.model_name: str = speaker_info["model_name"]
        self.model_number: str = speaker_info["model_number"]
        self.uid: str = speaker_info["uid"]
        self.display_version: str = speaker_info["display_version"]
        self.zone_name: str = speaker_info["zone_name"]
        self.player_icon: str = speaker_info["player_icon"]
        self.serial_number: str = speaker_info["serial_number"]

        self.available_features = []

        # Subscriptions and events
        self.subscriptions_failed: bool = False
        self._subscriptions: list[SubscriptionBase] = []
        # if zone_group_state_sub is not None:
        #     zone_group_state_sub.callback = self.async_dispatch_event
        #     self._subscriptions.append(zone_group_state_sub)
        self._subscription_lock: asyncio.Lock | None = None

        # self.activity_stats: ActivityStatistics = ActivityStatistics(self.zone_name)
        self._resub_cooldown_expires_at: float | None = None

        # Battery
        self.battery_info: dict[str, Any] = {}
        self._last_battery_event: datetime.datetime | None = None

        # Volume / Sound
        self.volume: int | None = None
        self.muted: bool | None = None
        self.cross_fade: bool | None = None
        self.balance: tuple[int, int] | None = None
        self.bass: int | None = None
        self.treble: int | None = None
        self.loudness: bool | None = None

        # Home theater
        self.audio_delay: int | None = None
        self.dialog_level: bool | None = None
        self.night_mode: bool | None = None
        self.sub_enabled: bool | None = None
        self.sub_crossover: int | None = None
        self.sub_gain: int | None = None
        self.surround_enabled: bool | None = None
        self.surround_mode: bool | None = None
        self.surround_level: int | None = None
        self.music_surround_level: int | None = None

        # Misc features
        self.buttons_enabled: bool | None = None
        self.mic_enabled: bool | None = None
        self.status_light: bool | None = None

        # Grouping
        self.coordinator: SonosSpeaker | None = None
        self.sonos_group: list[SonosSpeaker] = [self]
        self.soco_snapshot: Snapshot | None = None
        self.snapshot_group: list[SonosSpeaker] = []
        self._group_members_missing: set[str] = set()

        asyncio.create_task(self.async_subscribe())
        self._get_available_features()

    def get_info(self):
        return {
            "zone_name": self.zone_name,
            # "player_icon": self.player_icon,
            "uid": self.uid,
            "serial_number": self.serial_number,
            "software_version": self.software_version,
            "hardware_version": self.hardware_version,
            "model_number": self.model_number,
            "model_name": self.model_name.replace("Sonos ", ""),
            "display_version": self.display_version,
            "mac_address": self.mac_address,
            "ip_address": self.ip_address,
            "available_features": self.available_features
        }

    def to_dict(self):
        grouped = self.sonos_group != [self]
        media_dict = self.media.to_dict() if self.is_coordinator else self.coordinator.media.to_dict()
        return {
            'zone_name': self.zone_name,
            'model_name': self.model_name.replace("Sonos ", ""),
            'volume' : self.volume,
            'muted' : self.muted,
            'mic_enabled': self.mic_enabled,
            'cross_fade' : self.cross_fade,
            'balance' : self.balance,
            'bass' : self.bass,
            'treble' : self.treble,
            'loudness' : self.loudness,
            'is_coordinator': self.is_coordinator,
            'media': media_dict,
            'grouped': grouped,
            'group_name': self.sonos_group[0].zone_name if grouped else '',
            'battery_info': self.battery_info,
            'status_light': self.status_light,
            'buttons_enabled': self.buttons_enabled
        }

    def set_status_light(self, led_on: bool):
        """Switch on/off the speaker's status light."""
        self.soco.status_light = led_on
        self.status_light = led_on
        self.__change_cb(self)

    def set_buttons_enabled(self, enabled: bool):
        self.soco.buttons_enabled = enabled
        self.buttons_enabled = enabled
        self.__change_cb(self)

    def set_balance(self, balance: int):
        balance_tuple = (min(100, 100-balance), min(100, 100+balance))
        _LOGGER.debug("set to balance %i => %s", balance, balance_tuple)
        self.soco.balance = balance_tuple

    #
    # Properties
    #
    @property
    def alarms(self) -> SonosAlarms:
        """Return the SonosAlarms instance for this household."""
        return self.data.alarms[self.household_id]

    @property
    def is_coordinator(self) -> bool:
        """Return true if player is a coordinator."""
        return self.coordinator is None

    @property
    def subscription_address(self) -> str:
        """Return the current subscription callback address."""
        assert len(self._subscriptions) > 0
        addr, port = self._subscriptions[0].event_listener.address
        return ":".join([addr, str(port)])

    @property
    def missing_subscriptions(self) -> set[str]:
        """Return a list of missing service subscriptions."""
        subscribed_services = {sub.service.service_type for sub in self._subscriptions}
        return SUBSCRIPTION_SERVICES - subscribed_services


    def _get_available_features(self):
        features = []
        for feature_type in ALL_FEATURES:
            try:
                state = getattr(self.soco, feature_type, None)
                if state is not None:
                    setattr(self, feature_type, state)
                    features.append(feature_type)
            except SoCoSlaveException:
                features.append(feature_type)
        self.available_features = features
    #
    # Subscription handling and event dispatchers
    #
    def log_subscription_result(
        self, result: Any, event: str, level: int = logging.DEBUG
    ) -> None:
        """Log a message if a subscription action (create/renew/stop) results in an exception."""
        if not isinstance(result, Exception):
            return

        if isinstance(result, asyncio.exceptions.TimeoutError):
            message = "Request timed out"
            exc_info = None
        else:
            message = str(result)
            exc_info = result if not str(result) else None

        _LOGGER.log(
            level,
            "%s failed for %s: %s",
            event,
            self.zone_name,
            message,
            exc_info=exc_info,
        )

    async def async_subscribe(self) -> None:
        """Initiate event subscriptions under an async lock."""
        if not self._subscription_lock:
            self._subscription_lock = asyncio.Lock()

        async with self._subscription_lock:
            try:
                await self._async_subscribe()
            except SonosSubscriptionsFailed:
                _LOGGER.warning("Creating subscriptions failed for %s", self.zone_name)
                await self._async_offline()

    async def _async_subscribe(self) -> None:
        """Create event subscriptions."""
        subscriptions = [
            self._subscribe(getattr(self.soco, service), self.async_dispatch_event)
            for service in self.missing_subscriptions
        ]
        if not subscriptions:
            return

        _LOGGER.debug("Creating subscriptions for %s", self.zone_name)
        results = await asyncio.gather(*subscriptions, return_exceptions=True)
        for result in results:
            self.log_subscription_result(
                result, "Creating subscription", logging.WARNING
            )

        if any(isinstance(result, Exception) for result in results):
            raise SonosSubscriptionsFailed

    async def _subscribe(
        self, target: SubscriptionBase, sub_callback: Callable
    ) -> None:
        """Create a Sonos subscription."""
        subscription = await target.subscribe(
            auto_renew=True, requested_timeout=SUBSCRIPTION_TIMEOUT
        )
        subscription.callback = sub_callback
        subscription.auto_renew_fail = self.async_renew_failed
        self._subscriptions.append(subscription)

    async def async_unsubscribe(self) -> None:
        """Cancel all subscriptions."""
        if not self._subscriptions:
            return
        _LOGGER.debug("Unsubscribing from events for %s", self.zone_name)
        results = await asyncio.gather(
            *(subscription.unsubscribe() for subscription in self._subscriptions),
            return_exceptions=True,
        )
        for result in results:
            self.log_subscription_result(result, "Unsubscribe")
        self._subscriptions = []

    def async_renew_failed(self, exception: Exception) -> None:
        """Handle a failed subscription renewal."""
        asyncio.create_task(
            self._async_renew_failed(exception),
            name = "sonos renew failed"
        )

    async def _async_renew_failed(self, exception: Exception) -> None:
        """Mark the speaker as offline after a subscription renewal failure.

        This is to reset the state to allow a future clean subscription attempt.
        """
        if not self.available:
            return

        self.log_subscription_result(exception, "Subscription renewal", logging.WARNING)
        await self.async_offline()

    async def poll_status_light_and_buttons(self):
        has_changed = False
        _buttons_enabled = self.soco.buttons_enabled
        if _buttons_enabled != self.buttons_enabled:
            has_changed = True
            self.buttons_enabled = _buttons_enabled

        _status_light = self.soco.status_light
        if _status_light != self.status_light:
            has_changed = True
            self.status_light = _status_light

        if has_changed:
            self.__change_cb(self)

    def async_dispatch_event(self, event: SonosEvent) -> None:
        """Handle callback event and route as needed."""

        dispatcher = self._event_dispatchers[event.service.service_type]
        _LOGGER.debug("new event %s", event.service.service_type)
        dispatcher(self, event)

    def async_dispatch_alarms(self, event: SonosEvent) -> None:
        """Add the soco instance associated with the event to the callback."""
        if "alarm_list_version" not in event.variables:
            return
        asyncio.create_task(
            self.alarms.async_process_event(event, self),
            name = "sonos process event"
        )

    def async_dispatch_device_properties(self, event: SonosEvent) -> None:
        """Update device properties from an event."""
        asyncio.create_task(
            self.async_update_device_properties(event),
            name = "sonos device properties"
        )
        self.__change_cb(self)

    async def async_update_device_properties(self, event: SonosEvent) -> None:
        """Update device properties from an event."""
        if "mic_enabled" in event.variables:
            mic_exists = self.mic_enabled is not None
            self.mic_enabled = bool(int(event.variables["mic_enabled"]))
            # if not mic_exists:
            #     async_dispatcher_send(SONOS_CREATE_MIC_SENSOR, self)
            # TODO: send event to jeedom
        more_info = event.variables.get("more_info")
        if more_info:
            await self.async_update_battery_info(more_info)

    def async_dispatch_favorites(self, event: SonosEvent) -> None:
        """Add the soco instance associated with the event to the callback."""
        if "favorites_update_id" not in event.variables:
            return
        if "container_update_i_ds" not in event.variables:
            return
        #TODO: dynamically update favorites and send update to jeedom

    def async_dispatch_media_update(self, event: SonosEvent) -> None:
        """Update information about currently playing media from an event."""
        # The new coordinator can be provided in a media update event but
        # before the ZoneGroupState updates. If this happens the playback
        # state will be incorrect and should be ignored. Switching to the
        # new coordinator will use its media. The regrouping process will
        # be completed during the next ZoneGroupState update.
        av_transport_uri = event.variables.get("av_transport_uri", "")
        current_track_uri = event.variables.get("current_track_uri", "")
        if av_transport_uri == current_track_uri and av_transport_uri.startswith(
            "x-rincon:"
        ):
            new_coordinator_uid = av_transport_uri.split(":")[-1]
            new_coordinator_speaker = self.data.discovered.get(new_coordinator_uid)
            if new_coordinator_speaker:
                _LOGGER.debug(
                    "Media update coordinator (%s) received for %s",
                    new_coordinator_speaker.zone_name,
                    self.zone_name,
                )
                self.coordinator = new_coordinator_speaker
            else:
                _LOGGER.debug(
                    "Media update coordinator (%s) for %s not yet available",
                    new_coordinator_uid,
                    self.zone_name,
                )
            return

        crossfade = event.variables.get("current_crossfade_mode")
        if crossfade:
            crossfade = bool(int(crossfade))
            if self.cross_fade != crossfade:
                self.cross_fade = crossfade
                # self.async_write_entity_states()
                self.__change_cb(self)

        # Missing transport_state indicates a transient error
        new_status = event.variables.get("transport_state")
        if new_status is None:
            return

        # Ignore transitions, we should get the target state soon
        if new_status == SONOS_STATE_TRANSITIONING:
            return

        self.media.update_media_from_event(event.variables)
        self.__change_cb(self)

    def async_update_volume(self, event: SonosEvent) -> None:
        """Update information about currently volume settings."""
        variables = event.variables

        if "volume" in variables:
            volume = variables["volume"]
            self.volume = int(volume["Master"])
            if "LF" in volume and "RF" in volume:
                self.balance = (int(volume["LF"]), int(volume["RF"]))

        if "mute" in variables:
            self.muted = variables["mute"]["Master"] == "1"

        loudness = variables.get("loudness")
        if loudness:
            self.loudness = loudness["Master"] == "1"

        for bool_var in (
            "dialog_level",
            "night_mode",
            "sub_enabled",
            "surround_enabled",
            "surround_mode",
        ):
            if bool_var in variables:
                setattr(self, bool_var, variables[bool_var] == "1")

        for int_var in (
            "audio_delay",
            "bass",
            "treble",
            "sub_crossover",
            "sub_gain",
            "surround_level",
            "music_surround_level",
        ):
            if int_var in variables:
                setattr(self, int_var, variables[int_var])

        # self.async_write_entity_states()
        self.__change_cb(self)

    def speaker_activity(self, source: str) -> None:
        """Track the last activity on this speaker, set availability and resubscribe."""
        if self._resub_cooldown_expires_at:
            if time.monotonic() < self._resub_cooldown_expires_at:
                _LOGGER.debug(
                    "Activity on %s from %s while in cooldown, ignoring",
                    self.zone_name,
                    source,
                )
                return
            self._resub_cooldown_expires_at = None

        _LOGGER.debug("Activity on %s from %s", self.zone_name, source)
        was_available = self.available
        self.available = True
        if not was_available:
            self.__change_cb(self)
            asyncio.create_task(self.async_subscribe())

    async def _async_check_activity(self) -> None:
        """Validate availability of the speaker based on recent activity."""
        try:
            await self.hass.async_add_executor_job(self.ping)
        except SonosUpdateError:
            _LOGGER.warning(
                "No recent activity and cannot reach %s, marking unavailable",
                self.zone_name,
            )
            await self.async_offline()

    async def async_offline(self) -> None:
        """Handle removal of speaker when unavailable."""
        assert self._subscription_lock is not None
        async with self._subscription_lock:
            await self._async_offline()

    async def _async_offline(self) -> None:
        """Handle removal of speaker when unavailable."""
        if not self.available:
            return

        if self._resub_cooldown_expires_at is None:
            self._resub_cooldown_expires_at = time.monotonic() + RESUB_COOLDOWN_SECONDS
            _LOGGER.debug("Starting resubscription cooldown for %s", self.zone_name)

        self.available = False
        self.__change_cb(self)

        await self.async_unsubscribe()

        # self.data.discovery_known.discard(self.soco.uid)

    async def async_vanished(self, reason: str) -> None:
        """Handle removal of speaker when marked as vanished."""
        if not self.available:
            return
        _LOGGER.debug(
            "%s has vanished (%s), marking unavailable", self.zone_name, reason
        )
        await self.async_offline()

    async def async_rebooted(self) -> None:
        """Handle a detected speaker reboot."""
        _LOGGER.debug("%s rebooted, reconnecting", self.zone_name)
        await self.async_offline()
        self.speaker_activity("reboot")

    #
    # Battery management
    #
    async def fetch_battery_info(self) -> dict[str, Any]:
        """Fetch battery_info for the speaker."""
        battery_info = self.soco.get_battery_info()
        if not battery_info:
            # S1 firmware returns an empty payload
            raise S1BatteryMissing
        return battery_info

    async def async_update_battery_info(self, more_info: str) -> None:
        """Update battery info using a SonosEvent payload value."""
        battery_dict = dict(x.split(":") for x in more_info.split(","))
        for unused in UNUSED_DEVICE_KEYS:
            battery_dict.pop(unused, None)
        if not battery_dict:
            return
        if "BattChg" not in battery_dict:
            _LOGGER.debug(
                (
                    "Unknown device properties update for %s (%s),"
                    " please report an issue: '%s'"
                ),
                self.zone_name,
                self.model_name,
                more_info,
            )
            return

        self._last_battery_event = datetime.datetime.now()

        is_charging = EVENT_CHARGING[battery_dict["BattChg"]]

        self.battery_info.update(
            {
                "level": int(battery_dict["BattPct"]),
                "charging": is_charging,
            }
        )

        # if is_charging:
        #     # Poll to obtain current power source not provided by event
        #     try:
        #         self.battery_info = await self.fetch_battery_info()
        #     except SonosUpdateError as err:
        #         _LOGGER.debug("Could not request current power source: %s", err)


    # async def async_poll_battery(self, now: datetime.datetime | None = None) -> None:
    #     """Poll the device for the current battery state."""
    #     if not self.available:
    #         return

    #     if (
    #         self._last_battery_event
    #         and dt_util.utcnow() - self._last_battery_event < BATTERY_SCAN_INTERVAL
    #     ):
    #         return

    #     try:
    #         self.battery_info = await self.hass.async_add_executor_job(
    #             self.fetch_battery_info
    #         )
    #     except SonosUpdateError as err:
    #         _LOGGER.debug("Could not poll battery info: %s", err)
    #     else:
    #         self.async_write_entity_states() # TODO: send event

    # #
    # # Group management
    # #
    # def update_groups(self) -> None:
    #     """Update group topology when polling."""
    #     self.hass.add_job(self.create_update_groups_coro())

    # @callback
    # def async_update_group_for_uid(self, uid: str) -> None:
    #     """Update group topology if uid is missing."""
    #     if uid not in self._group_members_missing:
    #         return
    #     missing_zone = self.data.discovered[uid].zone_name
    #     _LOGGER.debug(
    #         "%s was missing, adding to %s group", missing_zone, self.zone_name
    #     )
    #     self.hass.async_create_task(self.create_update_groups_coro(), eager_start=True)

    # @callback
    def async_update_groups(self, event: SonosEvent) -> None:
        """Handle callback for topology change event."""
        xml = event.variables.get("zone_group_state")
        if xml:
            zgs = ET.fromstring(xml)
            for vanished_device in zgs.find("VanishedDevices") or []:
                reason = vanished_device.get("Reason")
                if reason not in SUPPORTED_VANISH_REASONS:
                    _LOGGER.debug(
                        "Ignoring %s marked %s as vanished with reason: %s",
                        self.zone_name,
                        vanished_device.get("ZoneName"),
                        reason,
                    )
                    continue
                uid = vanished_device.get("UUID")
                # async_dispatcher_send(
                #     self.hass,
                #     f"{SONOS_VANISHED}-{uid}",
                #     reason,
                # )
                # TODO: send event

        if "zone_player_uui_ds_in_group" not in event.variables:
            return
        asyncio.create_task(
            self.create_update_groups_coro(event),
            name=f"sonos group update {self.zone_name}"
        )

    def create_update_groups_coro(self, event: SonosEvent | None = None) -> Coroutine:
        """Handle callback for topology change event."""

        def _get_soco_group() -> list[str]:
            """Ask SoCo cache for existing topology."""
            coordinator_uid = self.soco.uid
            joined_uids = []

            with contextlib.suppress(OSError, SoCoException):
                if self.soco.group and self.soco.group.coordinator:
                    coordinator_uid = self.soco.group.coordinator.uid
                    joined_uids = [
                        p.uid
                        for p in self.soco.group.members
                        if p.uid != coordinator_uid and p.is_visible
                    ]

            return [coordinator_uid, *joined_uids]

        async def _async_extract_group(event: SonosEvent | None) -> list[str]:
            """Extract group layout from a topology event."""
            group = event and event.zone_player_uui_ds_in_group
            if group:
                assert isinstance(group, str)
                return group.split(",")
            asyncio.get_running_loop().run_in_executor(None, _get_soco_group)
            # return await self.hass.async_add_executor_job(_get_soco_group)

        def _async_regroup(group: list[str]) -> None:
            """Rebuild internal group layout."""
            if (
                group == [self.soco.uid]
                and self.sonos_group == [self]
            ):
                # Skip updating existing single speakers in polling mode
                return

            # entity_registry = er.async_get(self.hass)
            sonos_group = []

            for uid in group:
                speaker = self.data.discovered.get(uid)
                if speaker:
                    self._group_members_missing.discard(uid)
                    sonos_group.append(speaker)
                else:
                    self._group_members_missing.add(uid)
                    _LOGGER.debug(
                        "%s group member unavailable (%s), will try again",
                        self.zone_name,
                        uid,
                    )
                    return

            self.coordinator = None
            self.sonos_group = sonos_group
            self.__change_cb(self)

            for joined_uid in group[1:]:
                joined_speaker = self.data.discovered.get(joined_uid)
                if joined_speaker:
                    joined_speaker.coordinator = self
                    joined_speaker.sonos_group = sonos_group
                    self.__change_cb(joined_speaker)

            _LOGGER.debug("Regrouped %s: %s", self.zone_name, self.sonos_group)

        async def _async_handle_group_event(event: SonosEvent | None) -> None:
            """Get async lock and handle event."""

            async with self.data.topology_condition:
                group = await _async_extract_group(event)

                if self.soco.uid == group[0]:
                    _async_regroup(group)

                    self.data.topology_condition.notify_all()

            self.__change_cb(self)

        return _async_handle_group_event(event)

    # def join(self, speakers: list[SonosSpeaker]) -> list[SonosSpeaker]:
    #     """Form a group with other players."""
    #     if self.coordinator:
    #         self.unjoin()
    #         group = [self]
    #     else:
    #         group = self.sonos_group.copy()

    #     for speaker in speakers:
    #         if speaker.soco.uid != self.soco.uid:
    #             if speaker not in group:
    #                 speaker.soco.join(self.soco)
    #                 speaker.coordinator = self
    #                 group.append(speaker)

    #     return group

    # @staticmethod
    # async def join_multi(
    #     master: SonosSpeaker,
    #     speakers: list[SonosSpeaker],
    # ) -> None:
    #     """Form a group with other players."""
    #     async with hass.data[DATA_SONOS].topology_condition:
    #         group: list[SonosSpeaker] = await hass.async_add_executor_job(
    #             master.join, speakers
    #         )
    #         await SonosSpeaker.wait_for_groups(hass, [group])

    # @soco_error()
    # def unjoin(self) -> None:
    #     """Unjoin the player from a group."""
    #     if self.sonos_group == [self]:
    #         return
    #     self.soco.unjoin()
    #     self.coordinator = None

    # async def unjoin_multi(self, speakers: list[SonosSpeaker]) -> None:
    #     """Unjoin several players from their group."""

    #     async def _unjoin_all(speakers: list[SonosSpeaker]) -> None:
    #         """Sync helper."""
    #         # Detach all joined speakers first to prevent inheritance of queues
    #         coordinators = [s for s in speakers if s.is_coordinator]
    #         joined_speakers = [s for s in speakers if not s.is_coordinator]

    #         for speaker in joined_speakers + coordinators:
    #             speaker.unjoin()

    #     async with self.data.topology_condition:
    #         await _unjoin_all(speakers)
    #         await SonosSpeaker.wait_for_groups(self, [[s] for s in speakers])

    # @soco_error()
    def snapshot(self, with_group: bool) -> None:
        """Snapshot the state of a player."""
        self.soco_snapshot = Snapshot(self.soco)
        self.soco_snapshot.snapshot()
        if with_group:
            self.snapshot_group = self.sonos_group.copy()
        else:
            self.snapshot_group = []

    # @staticmethod
    # async def snapshot_multi(
    #    speakers: list[SonosSpeaker], with_group: bool
    # ) -> None:
    #     """Snapshot all the speakers and optionally their groups."""

    #     def _snapshot_all(speakers: Collection[SonosSpeaker]) -> None:
    #         """Sync helper."""
    #         for speaker in speakers:
    #             speaker.snapshot(with_group)

    #     # Find all affected players
    #     speakers_set = set(speakers)
    #     if with_group:
    #         for speaker in list(speakers_set):
    #             speakers_set.update(speaker.sonos_group)

    #     async with hass.data[DATA_SONOS].topology_condition:
    #         await hass.async_add_executor_job(_snapshot_all, speakers_set)

    # @soco_error()
    def restore(self) -> None:
        """Restore a snapshotted state to a player."""
        try:
            assert self.soco_snapshot is not None
            self.soco_snapshot.restore()
        except (TypeError, AssertionError, AttributeError, SoCoException) as ex:
            # Can happen if restoring a coordinator onto a current group member
            _LOGGER.warning("Error on restore %s: %s", self.zone_name, ex)
            if self.soco_snapshot.is_playing_queue:
                self.soco.play()

        self.soco_snapshot = None
        self.snapshot_group = []

    # @staticmethod
    # async def restore_multi(
    #     speakers: list[SonosSpeaker], with_group: bool
    # ) -> None:
    #     """Restore snapshots for all the speakers."""

    #     def _restore_groups(
    #         speakers: set[SonosSpeaker], with_group: bool
    #     ) -> list[list[SonosSpeaker]]:
    #         """Pause all current coordinators and restore groups."""
    #         for speaker in (s for s in speakers if s.is_coordinator):
    #             if (
    #                 speaker.media.playback_status == SONOS_STATE_PLAYING
    #                 and "Pause" in speaker.soco.available_actions
    #             ):
    #                 try:
    #                     speaker.soco.pause()
    #                 except SoCoUPnPException as exc:
    #                     _LOGGER.debug(
    #                         "Pause failed during restore of %s: %s",
    #                         speaker.zone_name,
    #                         speaker.soco.available_actions,
    #                         exc_info=exc,
    #                     )

    #         groups: list[list[SonosSpeaker]] = []
    #         if not with_group:
    #             return groups

    #         # Unjoin non-coordinator speakers not contained in the desired snapshot group
    #         #
    #         # If a coordinator is unjoined from its group, another speaker from the group
    #         # will inherit the coordinator's playqueue and its own playqueue will be lost
    #         speakers_to_unjoin = set()
    #         for speaker in speakers:
    #             if speaker.sonos_group == speaker.snapshot_group:
    #                 continue

    #             speakers_to_unjoin.update(
    #                 {
    #                     s
    #                     for s in speaker.sonos_group[1:]
    #                     if s not in speaker.snapshot_group
    #                 }
    #             )

    #         for speaker in speakers_to_unjoin:
    #             speaker.unjoin()

    #         # Bring back the original group topology
    #         for speaker in (s for s in speakers if s.snapshot_group):
    #             assert len(speaker.snapshot_group)
    #             if speaker.snapshot_group[0] == speaker:
    #                 if speaker.snapshot_group not in (speaker.sonos_group, [speaker]):
    #                     speaker.join(speaker.snapshot_group)
    #                 groups.append(speaker.snapshot_group.copy())

    #         return groups

    #     def _restore_players(speakers: Collection[SonosSpeaker]) -> None:
    #         """Restore state of all players."""
    #         for speaker in (s for s in speakers if not s.is_coordinator):
    #             speaker.restore()

    #         for speaker in (s for s in speakers if s.is_coordinator):
    #             speaker.restore()

    #     # Find all affected players
    #     speakers_set = {s for s in speakers if s.soco_snapshot}
    #     if missing_snapshots := set(speakers) - speakers_set:
    #         raise Error(
    #             "Restore failed, speakers are missing snapshots:"
    #             f" {[s.zone_name for s in missing_snapshots]}"
    #         )

    #     if with_group:
    #         for speaker in [s for s in speakers_set if s.snapshot_group]:
    #             assert len(speaker.snapshot_group)
    #             speakers_set.update(speaker.snapshot_group)

    #     async with hass.data[DATA_SONOS].topology_condition:
    #         groups = await hass.async_add_executor_job(
    #             _restore_groups, speakers_set, with_group
    #         )
    #         await SonosSpeaker.wait_for_groups(hass, groups)
    #         await hass.async_add_executor_job(_restore_players, speakers_set)

    # async def wait_for_groups(self,
    #     groups: list[list[SonosSpeaker]]
    # ) -> None:
    #     """Wait until all groups are present, or timeout."""

    #     def _test_groups(groups: list[list[SonosSpeaker]]) -> bool:
    #         """Return whether all groups exist now."""
    #         for group in groups:
    #             coordinator = group[0]

    #             # Test that coordinator is coordinating
    #             current_group = coordinator.sonos_group
    #             if coordinator != current_group[0]:
    #                 return False

    #             # Test that joined members match
    #             if set(group[1:]) != set(current_group[1:]):
    #                 return False

    #         return True

    #     try:
    #         async with asyncio.timeout(5):
    #             while not _test_groups(groups):
    #                 await self.data.topology_condition.wait()
    #     except TimeoutError:
    #         _LOGGER.warning("Timeout waiting for target groups %s", groups)

    #     any_speaker: SonosSpeaker = next(iter(self.data.discovered.values()))
    #     any_speaker.soco.zone_group_state.clear_cache()

    #
    # Media and playback state handlers
    #
    def update_volume(self) -> None:
        """Update information about current volume settings."""
        self.volume = self.soco.volume
        self.muted = self.soco.mute

    _event_dispatchers = {
        "AlarmClock": async_dispatch_alarms,
        "AVTransport": async_dispatch_media_update,
        "ContentDirectory": async_dispatch_favorites,
        "DeviceProperties": async_dispatch_device_properties,
        "RenderingControl": async_update_volume,
        "ZoneGroupTopology": async_update_groups,
    }