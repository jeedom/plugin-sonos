"""Const for Sonos."""

from __future__ import annotations

import datetime

SONOS_ARTIST = "artists"
SONOS_ALBUM = "albums"
SONOS_PLAYLISTS = "playlists"
SONOS_GENRE = "genres"
SONOS_ALBUM_ARTIST = "album_artists"
SONOS_TRACKS = "tracks"
SONOS_COMPOSER = "composers"
SONOS_RADIO = "radio"
SONOS_OTHER_ITEM = "other items"

SONOS_STATE_PLAYING = "PLAYING"
SONOS_STATE_TRANSITIONING = "TRANSITIONING"

SONOS_TYPES_MAPPING = {
    "A:ALBUM": SONOS_ALBUM,
    "A:ALBUMARTIST": SONOS_ALBUM_ARTIST,
    "A:ARTIST": SONOS_ARTIST,
    "A:COMPOSER": SONOS_COMPOSER,
    "A:GENRE": SONOS_GENRE,
    "A:PLAYLISTS": SONOS_PLAYLISTS,
    "A:TRACKS": SONOS_TRACKS,
    "object.container.album.musicAlbum": SONOS_ALBUM,
    "object.container.genre.musicGenre": SONOS_GENRE,
    "object.container.person.composer": SONOS_COMPOSER,
    "object.container.person.musicArtist": SONOS_ALBUM_ARTIST,
    "object.container.playlistContainer.sameArtist": SONOS_ARTIST,
    "object.container.playlistContainer": SONOS_PLAYLISTS,
    "object.item": SONOS_OTHER_ITEM,
    "object.item.audioItem.musicTrack": SONOS_TRACKS,
    "object.item.audioItem.audioBroadcast": SONOS_RADIO,
}

LIBRARY_TITLES_MAPPING = {
    "A:ALBUM": "Albums",
    "A:ALBUMARTIST": "Artists",
    "A:ARTIST": "Contributing Artists",
    "A:COMPOSER": "Composers",
    "A:GENRE": "Genres",
    "A:PLAYLISTS": "Playlists",
    "A:TRACKS": "Tracks",
}

SONOS_CREATE_ALARM = "sonos_create_alarm"
SONOS_CREATE_MIC_SENSOR = "sonos_create_mic_sensor"
SONOS_ALARMS_UPDATED = "sonos_alarms_updated"
SONOS_MEDIA_UPDATED = "sonos_media_updated"
SONOS_VANISHED = "sonos_vanished"

SOURCE_AIRPLAY = "AirPlay"
SOURCE_LINEIN = "Line-in"
SOURCE_SPOTIFY_CONNECT = "Spotify Connect"
SOURCE_TV = "TV"

MODELS_LINEIN_ONLY = (
    "CONNECT",
    "CONNECT:AMP",
    "PORT",
    "PLAY:5",
)
MODELS_TV_ONLY = (
    "ARC",
    "BEAM",
    "PLAYBAR",
    "PLAYBASE",
)
MODELS_LINEIN_AND_TV = ("AMP",)

ATTR_CROSSFADE = "cross_fade"
ATTR_LOUDNESS = "loudness"
ATTR_MUSIC_PLAYBACK_FULL_VOLUME = "surround_mode"
ATTR_NIGHT_SOUND = "night_mode"
ATTR_SPEECH_ENHANCEMENT = "dialog_level"
ATTR_STATUS_LIGHT = "status_light"
ATTR_SUB_ENABLED = "sub_enabled"
ATTR_SURROUND_ENABLED = "surround_enabled"
ATTR_TOUCH_CONTROLS = "buttons_enabled"
ATTR_MICROPHONE = "mic_enabled"

ALL_FEATURES = (
    ATTR_TOUCH_CONTROLS,
    ATTR_CROSSFADE,
    ATTR_LOUDNESS,
    ATTR_MUSIC_PLAYBACK_FULL_VOLUME,
    ATTR_NIGHT_SOUND,
    ATTR_SPEECH_ENHANCEMENT,
    ATTR_SUB_ENABLED,
    ATTR_SURROUND_ENABLED,
    ATTR_STATUS_LIGHT,
    ATTR_MICROPHONE
)

BATTERY_SCAN_INTERVAL = datetime.timedelta(minutes=15)
SCAN_INTERVAL = datetime.timedelta(seconds=10)
SUBSCRIPTION_TIMEOUT = 1200
