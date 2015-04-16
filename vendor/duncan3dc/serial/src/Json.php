<?php

namespace duncan3dc\Serial;

use duncan3dc\Serial\Exceptions\JsonException;

class Json extends AbstractSerial
{

    /**
     * Convert an array to a JSON string.
     *
     * {@inheritDoc}
     */
    public static function encode($array)
    {
        if (count($array) < 1) {
            return "";
        }

        $string = json_encode($array);

        static::checkLastError();

        return $string;
    }


    /**
     * Convert a JSON string to an array.
     *
     * {@inheritDoc}
     */
    public static function decode($string)
    {
        if (!$string) {
            return [];
        }

        $array = json_decode($string, true);

        static::checkLastError();

        return $array;
    }


    /**
     * Check if the last json operation returned an error and convert it to an exception.
     * Designed as an internal method called after any json operation,
     * but there's no reason it can't be called after a straight call to the php json_* functions.
     *
     * @return void
     */
    public static function checkLastError()
    {
        $error = json_last_error();

        if ($error == JSON_ERROR_NONE) {
            return;
        }

        $message = json_last_error_msg();

        throw new JsonException("JSON Error: " . $message, $error);
    }
}
