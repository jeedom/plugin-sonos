<?php

namespace duncan3dc\Sonos\Interfaces\Devices;

use Psr\Log\LoggerAwareInterface;

/**
 * Manage a group of devices.
 */
interface CollectionInterface extends LoggerAwareInterface
{

    /**
     * Add a device to this collection.
     *
     * @param DeviceInterface $device The device to add
     *
     * @return $this
     */
    public function addDevice(DeviceInterface $device): CollectionInterface;


    /**
     * Get all of the devices in this collection.
     *
     * @return DeviceInterface[]
     */
    public function getDevices(): array;


    /**
     * Remove all devices from this collection.
     *
     * @return $this
     */
    public function clear(): CollectionInterface;
}
