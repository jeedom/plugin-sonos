<?php

namespace duncan3dc\Serial;

use duncan3dc\Serial\Exceptions\PhpException;

class Php extends AbstractSerial
{

    /**
     * Convert an array to a php serialized string.
     *
     * {@inheritDoc}
     */
    public static function encode($array)
    {
        if (count($array) < 1) {
            return "";
        }

        try {
            $string = serialize($array);
        } catch (\Exception $e) {
            throw new PhpException("Serialize Error: " . $e->getMessage());
        }

        return $string;
    }


    /**
     * Convert a php serialized string to an array.
     *
     * {@inheritDoc}
     */
    public static function decode($string)
    {
        if (!$string) {
            return [];
        }

        try {
            $array = unserialize($string);
        } catch (\Exception $e) {
            throw new PhpException("Serialize Error: " . $e->getMessage());
        }

        return $array;
    }
}
