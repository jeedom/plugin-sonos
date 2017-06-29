<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInitb131b39c3e439adf68e754ff7761fe23
{
    public static $files = array (
        'a0edc8309cc5e1d60e3047b5df6b7052' => __DIR__ . '/..' . '/guzzlehttp/psr7/src/functions_include.php',
        'c964ee0ededf28c96ebd9db5099ef910' => __DIR__ . '/..' . '/guzzlehttp/promises/src/functions_include.php',
        '37a3dc5111fe8f707ab4c132ef1dbc62' => __DIR__ . '/..' . '/guzzlehttp/guzzle/src/functions_include.php',
    );

    public static $prefixLengthsPsr4 = array (
        'd' => 
        array (
            'duncan3dc\\Speaker\\Test\\' => 23,
            'duncan3dc\\Speaker\\' => 18,
            'duncan3dc\\Sonos\\' => 16,
            'duncan3dc\\SonosTests\\' => 21,
            'duncan3dc\\Log\\' => 14,
            'duncan3dc\\LogTests\\' => 19,
            'duncan3dc\\DomParser\\' => 20,
            'duncan3dc\\DomParserTests\\' => 25,
        ),
        'S' => 
        array (
            'Symfony\\Component\\Process\\' => 26,
        ),
        'R' => 
        array (
            'RobGridley\\Flysystem\\Smb\\' => 25,
        ),
        'P' => 
        array (
            'Psr\\SimpleCache\\' => 16,
            'Psr\\Http\\Message\\' => 17,
        ),
        'L' => 
        array (
            'League\\Flysystem\\' => 17,
        ),
        'I' => 
        array (
            'Icewind\\Streams\\Tests\\' => 22,
            'Icewind\\Streams\\' => 16,
            'Icewind\\SMB\\Test\\' => 17,
            'Icewind\\SMB\\' => 12,
        ),
        'G' => 
        array (
            'GuzzleHttp\\Psr7\\' => 16,
            'GuzzleHttp\\Promise\\' => 19,
            'GuzzleHttp\\' => 11,
        ),
        'D' => 
        array (
            'Doctrine\\Common\\Cache\\' => 22,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'duncan3dc\\Speaker\\Test\\' => 
        array (
            0 => __DIR__ . '/..' . '/duncan3dc/speaker/tests',
        ),
        'duncan3dc\\Speaker\\' => 
        array (
            0 => __DIR__ . '/..' . '/duncan3dc/speaker/src',
        ),
        'duncan3dc\\Sonos\\' => 
        array (
            0 => __DIR__ . '/..' . '/duncan3dc/sonos/src',
        ),
        'duncan3dc\\SonosTests\\' => 
        array (
            0 => __DIR__ . '/..' . '/duncan3dc/sonos/tests',
        ),
        'duncan3dc\\Log\\' => 
        array (
            0 => __DIR__ . '/..' . '/duncan3dc/logger-aware-trait/src',
        ),
        'duncan3dc\\LogTests\\' => 
        array (
            0 => __DIR__ . '/..' . '/duncan3dc/logger-aware-trait/tests',
        ),
        'duncan3dc\\DomParser\\' => 
        array (
            0 => __DIR__ . '/..' . '/duncan3dc/domparser/src',
        ),
        'duncan3dc\\DomParserTests\\' => 
        array (
            0 => __DIR__ . '/..' . '/duncan3dc/domparser/tests',
        ),
        'Symfony\\Component\\Process\\' => 
        array (
            0 => __DIR__ . '/..' . '/symfony/process',
        ),
        'RobGridley\\Flysystem\\Smb\\' => 
        array (
            0 => __DIR__ . '/..' . '/robgridley/flysystem-smb/src',
        ),
        'Psr\\SimpleCache\\' => 
        array (
            0 => __DIR__ . '/..' . '/psr/simple-cache/src',
        ),
        'Psr\\Http\\Message\\' => 
        array (
            0 => __DIR__ . '/..' . '/psr/http-message/src',
        ),
        'League\\Flysystem\\' => 
        array (
            0 => __DIR__ . '/..' . '/league/flysystem/src',
        ),
        'Icewind\\Streams\\Tests\\' => 
        array (
            0 => __DIR__ . '/..' . '/icewind/streams/tests',
        ),
        'Icewind\\Streams\\' => 
        array (
            0 => __DIR__ . '/..' . '/icewind/streams/src',
        ),
        'Icewind\\SMB\\Test\\' => 
        array (
            0 => __DIR__ . '/..' . '/icewind/smb/tests',
        ),
        'Icewind\\SMB\\' => 
        array (
            0 => __DIR__ . '/..' . '/icewind/smb/src',
        ),
        'GuzzleHttp\\Psr7\\' => 
        array (
            0 => __DIR__ . '/..' . '/guzzlehttp/psr7/src',
        ),
        'GuzzleHttp\\Promise\\' => 
        array (
            0 => __DIR__ . '/..' . '/guzzlehttp/promises/src',
        ),
        'GuzzleHttp\\' => 
        array (
            0 => __DIR__ . '/..' . '/guzzlehttp/guzzle/src',
        ),
        'Doctrine\\Common\\Cache\\' => 
        array (
            0 => __DIR__ . '/..' . '/doctrine/cache/lib/Doctrine/Common/Cache',
        ),
    );

    public static $prefixesPsr0 = array (
        'P' => 
        array (
            'Psr\\Log\\' => 
            array (
                0 => __DIR__ . '/..' . '/psr/log',
            ),
        ),
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInitb131b39c3e439adf68e754ff7761fe23::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInitb131b39c3e439adf68e754ff7761fe23::$prefixDirsPsr4;
            $loader->prefixesPsr0 = ComposerStaticInitb131b39c3e439adf68e754ff7761fe23::$prefixesPsr0;

        }, null, ClassLoader::class);
    }
}
