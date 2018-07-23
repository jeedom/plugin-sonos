<?php

namespace duncan3dc\Sonos\Interfaces\Utils;

interface SocketInterface {

	/**
	 * Send out the multicast discover request.
	 *
	 * @return string
	 */
	public function request();
}
