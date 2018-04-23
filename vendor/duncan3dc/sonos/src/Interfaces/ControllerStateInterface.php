<?php

namespace duncan3dc\Sonos\Interfaces;

use duncan3dc\Sonos\Interfaces\ControllerInterface;
use duncan3dc\Sonos\Interfaces\SpeakerInterface;
use duncan3dc\Sonos\Interfaces\TrackInterface;
use duncan3dc\Sonos\Tracks\Stream;
use duncan3dc\Sonos\Utils\Time;

/**
 * Representation of the current state of a controller.
*/
interface ControllerStateInterface
{
    /**
     * Get the playing mode of the controller.
     *
     * @return int One of the ControllerInterface::STATE_ constants
     */
    public function getState(): int;

    /**
     * Get the number of the active track in the queue
     *
     * @return int The zero-based number of the track in the queue
     */
    public function getTrack(): int;

    /**
     * Get the position of the currently active track.
     *
     * @return Time
     */
    public function getPosition(): Time;

    /**
     * Check if repeat is currently active.
     *
     * @return bool
     */
    public function getRepeat(): bool;

    /**
     * Check if shuffle is currently active.
     *
     * @return bool
     */
    public function getShuffle(): bool;

    /**
     * Check if crossfade is currently active.
     *
     * @return bool
     */
    public function getCrossfade(): bool;

    /**
     * Get the speakers that are in the group of this controller.
     *
     * @return SpeakerInterface[]
     */
    public function getSpeakers(): array;

    /**
     * Get the tracks that are in the queue.
     *
     * @return TrackInterface[]
     */
    public function getTracks(): array;

    /**
     * Get the stream this controller is using.
     *
     * @var Stream|null
     */
    public function getStream();
}
