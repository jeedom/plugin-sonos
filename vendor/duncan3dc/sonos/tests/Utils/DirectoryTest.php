<?php

namespace duncan3dc\SonosTests\Utils;

use duncan3dc\ObjectIntruder\Intruder;
use duncan3dc\Sonos\Utils\Directory;
use League\Flysystem\FilesystemInterface;
use Mockery;
use PHPUnit\Framework\TestCase;

class DirectoryTest extends TestCase
{
    private $directory;
    private $filesystem;


    public function setUp()
    {
        $this->filesystem = Mockery::mock(FilesystemInterface::class);
        $this->directory = new Directory($this->filesystem, "share/", "directory/");
    }


    public function tearDown()
    {
        Mockery::close();
    }


    public function testConstructor1()
    {
        $directory = new Directory(sys_get_temp_dir(), "share", "directory");
        $intruder = new Intruder($directory);
        $this->assertInstanceOf(FilesystemInterface::class, $intruder->filesystem);
    }
    public function testConstructor2()
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage("Invalid filesystem, must be an instance of " . FilesystemInterface::class . " or a string containing a local path");
        $directory = new Directory(44, "share", "directory");
    }
    public function testConstructor3()
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage("Invalid filesystem, must be an instance of " . FilesystemInterface::class . " or a string containing a local path");
        $directory = new Directory(new \DateTime, "share", "directory");
    }


    public function testGetSharePath()
    {
        $this->assertSame("share/directory", $this->directory->getSharePath());
    }


    public function testHas1()
    {
        $this->filesystem->shouldReceive("has")->once()->with("directory/file.txt")->andReturn(true);
        $this->assertTrue($this->directory->has("file.txt"));
    }
    public function testHas2()
    {
        $this->filesystem->shouldReceive("has")->once()->with("directory/stuff.txt")->andReturn(false);
        $this->assertFalse($this->directory->has("stuff.txt"));
    }


    public function testWrite()
    {
        $this->filesystem->shouldReceive("write")->once()->with("directory/file.txt", "ok");
        $this->assertSame($this->directory, $this->directory->write("file.txt", "ok"));
    }
}
