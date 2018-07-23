<?php

namespace duncan3dc\Sonos\Interfaces;

/**
 * Representation of a track.
 */
interface TrackInterface extends UriInterface {
	/**
	 * Set the name of the track.
	 */
	public function setTitle(string $title);

	/**
	 * Get the name of the track.
	 */
	public function getTitle();

	/**
	 * Set the artist of the track.
	 */
	public function setArtist(string $artist);

	/**
	 * Get the name of the artist of the track.
	 */
	public function getArtist();

	/**
	 * Set the album of the track.
	 */
	public function setAlbum(string $album);

	/**
	 * Get the name of the album of the track.
	 */
	public function getAlbum();

	/**
	 * Set the number of the track.
	 */
	public function setNumber(int $number);

	/**
	 * Get the track number.
	 */
	public function getNumber();

	/**
	 * Set the album art of the track.
	 */
	public function setAlbumArt(string $albumArt);

	/**
	 * @var string $albumArt The full path to the album art for this track.
	 */
	public function getAlbumArt();
}
