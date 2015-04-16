<?php

namespace duncan3dc\Serial;

use duncan3dc\Serial\Exceptions\SerialException;

class Serial
{
    protected $data;
    protected $format;

    public function __construct($data)
    {
        $this->data = $data;
        if (is_array($this->data)) {
            $this->format = "array";
        }
    }


    protected function call($format, $method)
    {
        $class = __NAMESPACE__ . "\\" . $format;
        if (!class_exists($class)) {
            throw new SerialException("Unrecognised format " . $format);
        }

        $this->data = forward_static_call([$class, $method], $this->data);
    }


    public function __call($method, array $args)
    {
        if (substr($method, 0, 4) == "from") {
            $format = substr($method, 4);

            $this->call($format, "decode");

            $this->format = "array";
            return $this;
        }

        if (substr($method, 0, 2) == "to") {
            $format = substr($method, 2);

            # If the data is already in the requested format then don't do anything
            if ($this->format === $format) {
                return $this;
            }

            # If we don't know the current format of the data then we can't do anything
            if (!$this->format) {
                throw new SerialException("Unknown input format, you must specify by calling ->fromJson(), ->fromYaml() or ->fromPhp()");
            }

            # If the current data is not in array format then convert it now
            if ($this->format !== "array") {
                $this->{"from" . $this->format}();
            }

            $this->call($format, "encode");

            $this->format = $format;
            return $this;
        }

        throw new SerialException("Unrecognised request " . $method . "()");
    }


    public function __toString()
    {
        return (string) $this->data;
    }


    public function toArray()
    {
        # If the current data is not in array format then convert it now
        if ($this->format !== "array") {
            $this->{"from" . $this->format}();
        }

        if (!is_array($this->data)) {
            return [];
        }

        return $this->data;
    }
}
