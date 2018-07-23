<?php

namespace duncan3dc\Sonos\Interfaces\Devices;

use Psr\Log\LoggerAwareInterface;
use Psr\Log\LoggerInterface;

/**
 * Manage a group of devices.
 */
interface CollectionInterface extends LoggerAwareInterface {
	/**
	 * Add a device to this collection.
	 *
	 * @param DeviceInterface $device The device to add
	 *
	 * @return $this
	 */
	public function addDevice(DeviceInterface $device);

	/**
	 * Add a device to this collection using its IP address
	 *
	 * @param string $address The IP address of the device to add
	 *
	 * @return $this
	 */
	public function addIp(string $address);

	/**
	 * Get all of the devices in this collection.
	 *
	 * @return DeviceInterface[]
	 */
	public function getDevices();

	/**
	 * Remove all devices from this collection.
	 *
	 * @return $this
	 */
	public function clear();

	/**
	 * Get the logger currently in use.
	 *
	 * @return LoggerInterface
	 */
	public function getLogger();
}
