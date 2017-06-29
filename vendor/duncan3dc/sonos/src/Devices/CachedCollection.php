<?php

namespace duncan3dc\Sonos\Devices;

use duncan3dc\Sonos\Interfaces\Devices\CollectionInterface;
use duncan3dc\Sonos\Interfaces\Devices\DeviceInterface;
use Psr\SimpleCache\CacheInterface;

/**
 * Cache the collection of devices.
 */
class CachedCollection extends Collection
{
    const CACHE_KEY = "device-ip-addresses-2.0.0";

    /**
     * @var bool $retrieved A flag to indicate whether we've retrieved the devices from cache yet or not.
     */
    private $retrieved = false;

    /**
     * @var CollectionInterface $collection The device collection to actually use.
     */
    private $collection;

    /**
     * @var CacheInterface $cache The cache object to use for the expensive multicast discover to find Sonos devices on the network.
     */
    private $cache;


    /**
     * Create a new instance.
     *
     * @param CollectionInterface $collection The device collection to actually use
     * @param CacheInterface $cache The cache object to use for the expensive multicast discover to find Sonos devices on the network
     */
    public function __construct(CollectionInterface $collection, CacheInterface $cache)
    {
        $this->collection = $collection;
        $this->cache = $cache;
    }


    /**
     * Get all of the devices on the current network
     *
     * @return DeviceInterface[]
     */
    public function getDevices(): array
    {
        # If we've already retrieved the devices from cache then just return them
        if ($this->retrieved) {
            return parent::getDevices();
        }

        # If we haven't cached the available addresses yet then do it now
        if (!$this->cache->has(self::CACHE_KEY)) {
            $addresses = [];
            foreach ($this->collection->getDevices() as $device) {
                $addresses[] = $device->getIp();
            }
            $this->cache->set(self::CACHE_KEY, $addresses);
        }

        $addresses = $this->cache->get(self::CACHE_KEY);
        foreach ($addresses as $address) {
            $this->addIp($address);
        }
        $this->retrieved = true;

        return parent::getDevices();
    }
}
