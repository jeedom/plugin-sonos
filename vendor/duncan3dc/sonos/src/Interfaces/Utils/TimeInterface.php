<?php

namespace duncan3dc\Sonos\Interfaces\Utils;

/**
 * A class to represent amounts of time.
 */
interface TimeInterface {

	/**
	 * Get the number of seconds this instance represents.
	 *
	 * @return int
	 */
	public function asInt();

	/**
	 * Get the time in the format hh:mm:ss.
	 *
	 * @return string
	 */
	public function asString();

	/**
	 * Get the time in the format hh:mm:ss.
	 *
	 * @return string
	 */
	public function __toString();

	/**
	 * Get the seconds portion of the time.
	 *
	 * @return int
	 */
	public function getSeconds();

	/**
	 * Get the minutes portion of the time.
	 *
	 * @return int
	 */
	public function getMinutes();

	/**
	 * Get the hours portion of the time.
	 *
	 * @return int
	 */
	public function getHours();

	/**
	 * Format the time in a custom way.
	 *
	 * @param string $format The custom format to use. %h, %m, %s are available,
	 *                  and uppercase versions (%H, %M, %S) ensure a leading zero is present for single digit values
	 *
	 * @return string
	 */
	public function format(string $format);
}
