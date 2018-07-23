<?php

namespace duncan3dc\Sonos\Interfaces;

use duncan3dc\Sonos\Interfaces\ControllerInterface;
use duncan3dc\Sonos\Interfaces\PlaylistInterface;
use duncan3dc\Sonos\Interfaces\Services\RadioInterface;
use duncan3dc\Sonos\Interfaces\SpeakerInterface;

/**
 * Provides methods to locate speakers/controllers/playlists on the current network.
 */
interface NetworkInterface {
	/**
	 * Get all the speakers on the network.
	 *
	 * @return SpeakerInterface[]
	 */
	public function getSpeakers();

	/**
	 * Get a Controller instance from the network.
	 *
	 * Useful for managing playlists/alarms, as these need a controller but it doesn't matter which one.
	 *
	 * @return ControllerInterface
	 */
	public function getController();

	/**
	 * Get a speaker with the specified room name.
	 *
	 * @param string $room The name of the room to look for
	 *
	 * @return SpeakerInterface
	 */
	public function getSpeakerByRoom(string $room);

	/**
	 * Get all the speakers with the specified room name.
	 *
	 * @param string $room The name of the room to look for
	 *
	 * @return SpeakerInterface[]
	 */
	public function getSpeakersByRoom(string $room);

	/**
	 * Get all the coordinators on the network.
	 *
	 * @return ControllerInterface[]
	 */
	public function getControllers();

	/**
	 * Get the coordinator for the specified room name.
	 *
	 * @param string $room The name of the room to look for
	 *
	 * @return ControllerInterface
	 */
	public function getControllerByRoom(string $room);

	/**
	 * Get the coordinator for the specified ip address.
	 *
	 * @param string $ip The ip address of the speaker
	 *
	 * @return ControllerInterface
	 */
	public function getControllerByIp(string $ip);

	/**
	 * Get all the playlists available on the network.
	 *
	 * @return PlaylistInterface[]
	 */
	public function getPlaylists();

	/**
	 * Check if a playlist with the specified name exists on this network.
	 *
	 * If no case-sensitive match is found it will return a case-insensitive match.
	 *
	 * @param string $name The name of the playlist
	 *
	 * @return bool
	 */
	public function hasPlaylist(string $name);

	/**
	 * Get the playlist with the specified name.
	 *
	 * If no case-sensitive match is found it will return a case-insensitive match.
	 *
	 * @param string $name The name of the playlist
	 *
	 * @return PlaylistInterface
	 */
	public function getPlaylistByName(string $name);

	/**
	 * Get the playlist with the specified id.
	 *
	 * @param string $id The ID of the playlist (eg SQ:123)
	 *
	 * @return PlaylistInterface
	 */
	public function getPlaylistById(string $id);

	/**
	 * Create a new playlist.
	 *
	 * @param string $name The name to give to the playlist
	 *
	 * @return PlaylistInterface
	 */
	public function createPlaylist(string $name);

	/**
	 * Get all the alarms available on the network.
	 *
	 * @return AlarmInterface[]
	 */
	public function getAlarms();

	/**
	 * Get the alarm from the specified id.
	 *
	 * @param int $id The ID of the alarm
	 *
	 * @return AlarmInterface
	 */
	public function getAlarmById(int $id);

	/**
	 * Get a Radio instance for the network.
	 *
	 * @return RadioInterface
	 */
	public function getRadio();
}
