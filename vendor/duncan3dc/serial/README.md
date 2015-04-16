serial
======

A collection of PHP serialization helpers with a consistent interface for each.

[![Build Status](https://travis-ci.org/duncan3dc/serial.svg?branch=master)](https://travis-ci.org/duncan3dc/serial)
[![Latest Stable Version](https://poser.pugx.org/duncan3dc/serial/version.svg)](https://packagist.org/packages/duncan3dc/serial)


Available Classes
=================

* Json (using the native json_* functions)
* Yaml (using the Symfony Yaml component)


Interface
=========

All serialization classes implement the interface [duncan3dc\Serial\SerialInterface](src/SerialInterface.php)


Examples
========

Convert array data to string format
```php
use duncan3dc\Serial\Json;
$data = BusinessLogic::getDataAsArray();
$json = Json::encode($data);
```

Convert string formatted data to an array
```php
use duncan3dc\Serial\Yaml;
$yaml = Api::getYamlResponse($request);
$response = Yaml::decode($yaml);
```

Convient methods to serialize and store data on disk
```php
use duncan3dc\Serial\Json;
$filename = "/tmp/file.json";
$data = BusinessLogic::getDataAsArray();
Json::encodeToFile($filename, $data);
```

Retrieve previously stored data from disk
```php
use duncan3dc\Serial\Json;
$filename = "/tmp/file.json";
$data = Json::decodeFromFile($filename);
```


Serial Helper
=============

There is a Serial class that allows for juggling/guessing formats
```php
use duncan3dc\Serial\Serial;
$data = BusinessLogic::getDataAsArray();
$serial = new Serial($data);
$json = (string)$serial->toJson();
$yaml = (string)$serial->toYaml();
$data = $serial->toArray();
```

All the methods return the instance making them chainable
```php
use duncan3dc\Serial\Serial;
$json = Api::getJsonResponse($request);
$yaml = (new Serial($json))->fromJson()->toYaml();
```

Then serialized string is available by casting the Serial class to a string, and php array is available by calling the toArray() method
