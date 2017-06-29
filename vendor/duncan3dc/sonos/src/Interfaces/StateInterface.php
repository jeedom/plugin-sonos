<?php

namespace duncan3dc\Sonos\Interfaces;

use duncan3dc\Sonos\Interfaces\TrackInterface;
use duncan3dc\Sonos\Tracks\Stream;
use duncan3dc\Sonos\Utils\Time;

/**
 * Representation of the current state of a controller.
 */
interface StateInterface extends TrackInterface
{

    /**
     * Check if this state is currently playing a stream.
     *
     * @return bool
     */
    public function isStreaming(): bool;

    /**
     * Set the stream object in use.
     *
     * @param Stream $stream The stream
     *
     * @return StateInterface
     */
    public function setStream(Stream $stream): StateInterface;

    /**
     * Get the stream object in use (or null if we are not on a stream).
     *
     * @return TrackInterface|null
     */
    public function getStream();

    /**
     * Set the duration of the currently active track.
     *
     * @param Time $duration The duration
     *
     * @return StateInterface
     */
    public function setDuration(Time $duration): StateInterface;

    /**
     * Get the duration of the currently active track.
     */
    public function getDuration(): Time;

    /**
     * Set the position of the currently active track.
     *
     * @param Time $position The position
     *
     * @return StateInterface
     */
    public function setPosition(Time $position): StateInterface;

    /**
     * Get the position of the currently active track.
     */
    public function getPosition(): Time;
}
