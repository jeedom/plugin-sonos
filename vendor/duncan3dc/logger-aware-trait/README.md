# logger-aware-trait
A PSR-3 trait allowing any class to easily add logger support

[![Latest Stable Version](https://poser.pugx.org/duncan3dc/logger-aware-trait/version.svg)](https://packagist.org/packages/duncan3dc/logger-aware-trait)
[![Build Status](https://travis-ci.org/duncan3dc/logger-aware-trait.svg?branch=master)](https://travis-ci.org/duncan3dc/logger-aware-trait)
[![Coverage Status](https://coveralls.io/repos/github/duncan3dc/logger-aware-trait/badge.svg?branch=master)](https://coveralls.io/github/duncan3dc/logger-aware-trait)


## Installing

The recommended method of installing this library is via [Composer](//getcomposer.org/).

Run the following command from your project root:

```bash
$ composer require duncan3dc/logger-aware-trait
```

## Setup

Simply apply the trait to your classes:

```php
use duncan3dc\Log\LoggerAwareTrait;

class MyClass
{
    use LoggerAwareTrait;
}
```

Now the class will have a `getLogger()` method that you can safely call.  
If a logger has been injected using `setLogger()` then that logger will be provided, otherwise a `NullLogger` will be used instead.


## Changelog
A [Changelog](CHANGELOG.md) has been available since the beginning of time


Where to get help
-----------------
Found a bug? Got a question? Just not sure how something works?  
Please [create an issue](//github.com/duncan3dc/logger-aware-trait/issues) and I'll do my best to help out.  
Alternatively you can catch me on [Twitter](https://twitter.com/duncan3dc)
