"""Support for media metadata handling."""

from __future__ import annotations

import datetime
from typing import Any
from datetime import timedelta
import voluptuous as vol

from soco.core import (
    MUSIC_SRC_AIRPLAY,
    MUSIC_SRC_LINE_IN,
    MUSIC_SRC_RADIO,
    MUSIC_SRC_SPOTIFY_CONNECT,
    MUSIC_SRC_TV,
    SoCo,
)
from soco.data_structures import DidlAudioBroadcast, DidlPlaylistContainer
from soco.music_library import MusicLibrary

from .const import (
    SONOS_STATE_PLAYING,
    SONOS_STATE_TRANSITIONING,
    SOURCE_AIRPLAY,
    SOURCE_LINEIN,
    SOURCE_SPOTIFY_CONNECT,
    SOURCE_TV,
)

LINEIN_SOURCES = (MUSIC_SRC_TV, MUSIC_SRC_LINE_IN)
SOURCE_MAPPING = {
    MUSIC_SRC_AIRPLAY: SOURCE_AIRPLAY,
    MUSIC_SRC_TV: SOURCE_TV,
    MUSIC_SRC_LINE_IN: SOURCE_LINEIN,
    MUSIC_SRC_SPOTIFY_CONNECT: SOURCE_SPOTIFY_CONNECT,
}
UNAVAILABLE_VALUES = {"", "NOT_IMPLEMENTED", None}
DURATION_SECONDS = "duration_in_s"
POSITION_SECONDS = "position_in_s"


def time_period_str(value: str) -> timedelta:
    """Validate and transform time offset."""
    if isinstance(value, int):  # type: ignore[unreachable]
        raise vol.Invalid("Make sure you wrap time values in quotes")
    if not isinstance(value, str):
        raise vol.Invalid(TIME_PERIOD_ERROR.format(value))

    negative_offset = False
    if value.startswith("-"):
        negative_offset = True
        value = value[1:]
    elif value.startswith("+"):
        value = value[1:]

    parsed = value.split(":")
    if len(parsed) not in (2, 3):
        raise vol.Invalid(TIME_PERIOD_ERROR.format(value))
    try:
        hour = int(parsed[0])
        minute = int(parsed[1])
        try:
            second = float(parsed[2])
        except IndexError:
            second = 0
    except ValueError as err:
        raise vol.Invalid(TIME_PERIOD_ERROR.format(value)) from err

    offset = timedelta(hours=hour, minutes=minute, seconds=second)

    if negative_offset:
        offset *= -1

    return offset


def _timespan_secs(timespan: str | None) -> int | None:
    """Parse a time-span into number of seconds."""
    if timespan in UNAVAILABLE_VALUES:
        return None
    return int(time_period_str(timespan).total_seconds())  # type: ignore[arg-type]


class SonosMedia:
    """Representation of the current Sonos media."""

    def __init__(self, soco: SoCo) -> None:
        """Initialize a SonosMedia."""
        self.soco = soco
        self.play_mode: str | None = None
        self.playback_status: str | None = None

        # This block is reset with clear()
        self.album_name: str | None = None
        self.artist: str | None = None
        self.channel: str | None = None
        self.duration: float | None = None
        self.image_url: str | None = None
        self.queue_position: int | None = None
        self.queue_size: int | None = None
        self.playlist_name: str | None = None
        self.source_name: str | None = None
        self.title: str | None = None
        self.uri: str | None = None

        self.position: int | None = None
        self.position_updated_at: datetime.datetime | None = None

    def to_dict(self):
        return {
            'play_mode' : self.play_mode if self.play_mode is not None else '',
            'playback_status' : self.playback_status if self.playback_status is not None else '',
            'album_name' : self.album_name if self.album_name is not None else '',
            'artist' : self.artist if self.artist is not None else '',
            'channel' : self.channel if self.channel is not None else '',
            'duration' : self.duration if self.duration is not None else 0,
            'image_url' : self.image_url if self.image_url is not None else '',
            'queue_position' : self.queue_position if self.queue_position is not None else 0,
            'queue_size' : self.queue_size if self.queue_size is not None else 0,
            'playlist_name' : self.playlist_name if self.playlist_name is not None else '',
            'source_name' : self.source_name if self.source_name is not None else '',
            'title' : self.title if self.title is not None else '',
            'uri' : self.uri if self.uri is not None else '',
            'position' : self.position if self.position is not None else 0
        }

    def clear(self) -> None:
        """Clear basic media info."""
        self.album_name = None
        self.artist = None
        self.channel = None
        self.duration = None
        self.image_url = None
        self.playlist_name = None
        self.queue_position = None
        self.queue_size = None
        self.source_name = None
        self.title = None
        self.uri = None

    def clear_position(self) -> None:
        """Clear the position attributes."""
        self.position = None
        self.position_updated_at = None

    @property
    def library(self) -> MusicLibrary:
        """Return the soco MusicLibrary instance."""
        return self.soco.music_library

    def poll_track_info(self) -> dict[str, Any]:
        """Poll the speaker for current track info, add converted position values."""
        track_info: dict[str, Any] = self.soco.get_current_track_info()
        track_info[DURATION_SECONDS] = _timespan_secs(track_info.get("duration"))
        track_info[POSITION_SECONDS] = _timespan_secs(track_info.get("position"))
        return track_info

    def write_media_player_states(self) -> None:
        """Send a signal to media player(s) to write new states."""
        # dispatcher_send(self.hass, SONOS_MEDIA_UPDATED, self.soco.uid) # TODO: send event to jeedom

    def set_basic_track_info(self, update_position: bool = False) -> None:
        """Query the speaker to update media metadata and position info."""
        self.clear()

        track_info = self.poll_track_info()
        if not track_info["uri"]:
            return
        self.uri = track_info["uri"]

        audio_source = self.soco.music_source_from_uri(self.uri)
        if source := SOURCE_MAPPING.get(audio_source):
            self.source_name = source
            if audio_source in LINEIN_SOURCES:
                self.clear_position()
                self.title = source
                return

        self.artist = track_info.get("artist")
        self.album_name = track_info.get("album")
        self.title = track_info.get("title")
        self.image_url = track_info.get("album_art")

        playlist_position = int(track_info.get("playlist_position", -1))
        if playlist_position > 0:
            self.queue_position = playlist_position

        self.update_media_position(track_info, force_update=update_position)

    def update_media_from_event(self, evars: dict[str, Any]) -> None:
        """Update information about currently playing media using an event payload."""
        new_status = evars["transport_state"]
        state_changed = new_status != self.playback_status

        self.play_mode = evars["current_play_mode"]
        self.playback_status = new_status

        track_uri = evars["enqueued_transport_uri"] or evars["current_track_uri"]
        audio_source = self.soco.music_source_from_uri(track_uri)

        self.set_basic_track_info(update_position=state_changed)

        if ct_md := evars["current_track_meta_data"]:
            if not self.image_url:
                if album_art_uri := getattr(ct_md, "album_art_uri", None):
                    self.image_url = self.library.build_album_art_full_uri(
                        album_art_uri
                    )

        et_uri_md = evars["enqueued_transport_uri_meta_data"]
        if isinstance(et_uri_md, DidlPlaylistContainer):
            self.playlist_name = et_uri_md.title

        if queue_size := evars.get("number_of_tracks", 0):
            self.queue_size = int(queue_size)

        if audio_source == MUSIC_SRC_RADIO:
            if et_uri_md:
                self.channel = et_uri_md.title

            # Extra guards for S1 compatibility
            if ct_md and hasattr(ct_md, "radio_show") and ct_md.radio_show:
                radio_show = ct_md.radio_show.split(",")[0]
                self.channel = " • ".join(filter(None, [self.channel, radio_show]))

            if isinstance(et_uri_md, DidlAudioBroadcast):
                self.title = self.title or self.channel

        self.write_media_player_states()

    def poll_media(self) -> None:
        """Poll information about currently playing media."""
        transport_info = self.soco.get_current_transport_info()
        new_status = transport_info["current_transport_state"]

        if new_status == SONOS_STATE_TRANSITIONING:
            return

        update_position = new_status != self.playback_status
        self.playback_status = new_status
        self.play_mode = self.soco.play_mode

        self.set_basic_track_info(update_position=update_position)

        self.write_media_player_states()

    def update_media_position(
        self, position_info: dict[str, int], force_update: bool = False
    ) -> None:
        """Update state when playing music tracks."""
        duration = position_info.get(DURATION_SECONDS)
        current_position = position_info.get(POSITION_SECONDS)

        if not (duration or current_position):
            self.clear_position()
            return

        should_update = force_update
        self.duration = duration

        # player started reporting position?
        if current_position is not None and self.position is None:
            should_update = True

        # position jumped?
        if current_position is not None and self.position is not None:
            if self.playback_status == SONOS_STATE_PLAYING:
                assert self.position_updated_at is not None
                time_delta = datetime.datetime.now() - self.position_updated_at
                time_diff = time_delta.total_seconds()
            else:
                time_diff = 0

            calculated_position = self.position + time_diff

            if abs(calculated_position - current_position) > 1.5:
                should_update = True

        if current_position is None:
            self.clear_position()
        elif should_update:
            self.position = current_position
            self.position_updated_at = datetime.datetime.now()
