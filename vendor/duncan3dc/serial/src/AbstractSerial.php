<?php

namespace duncan3dc\Serial;

use duncan3dc\Helpers\Helper;
use duncan3dc\Serial\Exceptions\FileException;

abstract class AbstractSerial implements SerialInterface
{

    /**
     * Convert the passed variable between array and the serial format (depending on the type passed).
     *
     * {@inheritDoc}
     */
    public static function convert($data, $options = null)
    {
        $options = Helper::getOptions($options, [
            "cleanup"   =>  false,
        ]);

        # If the data is an array the assume we are encoding it
        if (is_array($data)) {
            if ($options["cleanup"]) {
                $data = Helper::cleanupArray($data);
            }
            return static::encode($data);

        # If the data isn't an array then assume we decoding it
        } else {
            $array = static::decode($data);
            if ($options["cleanup"]) {
                $array = Helper::cleanupArray($array);
            }
            return $array;
        }
    }


    /**
     * Convert an array to a serial string, and then write it to a file.
     *
     * {@inheritDoc}
     */
    public static function encodeToFile($path, $array)
    {
        $string = static::encode($array);

        # Ensure the directory exists
        $directory = pathinfo($path, PATHINFO_DIRNAME);
        if (!is_dir($directory)) {
            mkdir($directory, 0775, true);
        }

        if (file_put_contents($path, $string) === false) {
            throw new FileException("Failed to write the file (" . $path . ")");
        }
    }


    /**
     * Read a serial string from a file and convert it to an array.
     *
     * {@inheritDoc}
     */
    public static function decodeFromFile($path)
    {
        if (!is_file($path)) {
            throw new FileException("File does not exist (" . $path . ")");
        }

        $string = file_get_contents($path);

        if ($string === false) {
            throw new FileException("Failed to read the file (" . $path . ")");
        }

        return static::decode($string);
    }
}
