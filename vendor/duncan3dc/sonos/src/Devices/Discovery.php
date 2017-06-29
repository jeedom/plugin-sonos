<?php

namespace duncan3dc\Sonos\Devices;

use duncan3dc\Sonos\Interfaces\Devices\FactoryInterface;
use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;

class Discovery extends Collection {
	/**
	 * @var bool $discovered A flag to indicate whether we've discovered the devices yet or not.
	 */
	private $discovered = false;

	/**
	 * @var string $networkInterface The network interface to use for SSDP discovery.
	 */
	private $networkInterface;

	/**
	 * @var string $multicastAddress The multicast address to use for SSDP discovery.
	 */
	private $multicastAddress = "239.255.255.250";

	/**
	 * @var LoggerInterface $logger The logging object.
	 */
	private $logger;

	/**
	 * Create a new instance.
	 *
	 * @param FactoryInterface $factory The factory to create new devices from
	 * @param LoggerInterface $logger A logging object
	 */
	public function __construct(FactoryInterface $factory, LoggerInterface $logger = null) {
		parent::__construct($factory);

		if ($logger === null) {
			$logger = new NullLogger;
		}
		$this->logger = $logger;
	}

	/**
	 * Set the network interface to use for SSDP discovery.
	 *
	 * See the documentation on IP_MULTICAST_IF at http://php.net/manual/en/function.socket-get-option.php
	 *
	 * @var string|int $networkInterface The interface to use
	 *
	 * @return $this
	 */
	public function setNetworkInterface($networkInterface): Discovery{
		$this->networkInterface = $networkInterface;

		$this->discovered = false;

		return $this;
	}

	/**
	 * Get the network interface currently in use
	 *
	 * @return string|int|null The network interface name
	 */
	public function getNetworkInterface() {
		return $this->networkInterface;
	}

	/**
	 * Set the multicast address to use for SSDP discovery.
	 *
	 * @var string $multicastAddress The address to use
	 *
	 * @return $this
	 */
	public function setMulticastAddress(string $multicastAddress): Discovery{
		$this->multicastAddress = $multicastAddress;

		$this->discovered = false;

		return $this;
	}

	/**
	 * Get the multicast address to use for SSDP discovery.
	 *
	 * @return string The address to use
	 */
	public function getMulticastAddress(): string {
		return $this->multicastAddress;
	}

	/**
	 * Get all of the devices on the current network
	 *
	 * @return DeviceInterface[]
	 */
	public function getDevices(): array
	{
		if (!$this->discovered) {
			$this->discoverDevices();
			$this->discovered = true;
		}

		return parent::getDevices();
	}

	/**
	 * Get all the devices on the current network.
	 *
	 * @return void
	 */
	private function discoverDevices() {
		$this->logger->info("discovering devices...");

		$port = 1900;

		$sock = socket_create(AF_INET, SOCK_DGRAM, SOL_UDP);

		$level = getprotobyname("ip");

		socket_set_option($sock, $level, IP_MULTICAST_TTL, 2);

		if ($this->getNetworkInterface() !== null) {
			socket_set_option($sock, $level, IP_MULTICAST_IF, $this->getNetworkInterface());
		}

		$data = "M-SEARCH * HTTP/1.1\r\n";
		$data .= "HOST: " . $this->getMulticastAddress() . ":reservedSSDPport\r\n";
		$data .= "MAN: ssdp:discover\r\n";
		$data .= "MX: 1\r\n";
		$data .= "ST: urn:schemas-upnp-org:device:ZonePlayer:1\r\n";

		$this->logger->debug($data);

		socket_sendto($sock, $data, strlen($data), null, $this->getMulticastAddress(), $port);

		$read = [$sock];
		$write = [];
		$except = [];
		$name = null;
		$port = null;
		$tmp = "";

		$response = "";
		while (socket_select($read, $write, $except, 1)) {
			socket_recvfrom($sock, $tmp, 2048, null, $name, $port);
			$response .= $tmp;
		}

		$this->logger->debug($response);

		$devices = [];
		foreach (explode("\r\n\r\n", $response) as $reply) {
			if (!$reply) {
				continue;
			}

			$data = [];
			foreach (explode("\r\n", $reply) as $line) {
				if (!$pos = strpos($line, ":")) {
					continue;
				}
				$key = strtolower(substr($line, 0, $pos));
				$val = trim(substr($line, $pos + 1));
				$data[$key] = $val;
			}
			$devices[] = $data;
		}

		$unique = [];
		foreach ($devices as $device) {
			if ($device["st"] !== "urn:schemas-upnp-org:device:ZonePlayer:1") {
				continue;
			}
			if (in_array($device["usn"], $unique)) {
				continue;
			}
			$this->logger->info("found device: {usn}", $device);

			$unique[] = $device["usn"];

			$url = parse_url($device["location"]);
			$this->addIp($url["host"]);
		}

		return $this;
	}
}
