<?php

namespace duncan3dc\SonosTests;

use duncan3dc\Sonos\Network;
use PHPUnit\Framework\TestCase;

class NetworkTest extends TestCase
{
    private $network;

    public function setUp()
    {
        $this->network = new Network;
    }
}
