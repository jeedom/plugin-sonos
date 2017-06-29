<?php

namespace duncan3dc\Log;

use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;

/**
 * Basic Implementation of LoggerAwareInterface.
 */
trait LoggerAwareTrait
{
    /**
     * @var LoggerInterface $logger The logger instance in use.
     */
    private $logger;


    /**
     * Set the logger to be used by this class.
     *
     * @param LoggerInterface $logger The logger to use
     *
     * @return $this
     */
    public function setLogger(LoggerInterface $logger)
    {
        $this->logger = $logger;

        return $this;
    }


    /**
     * Get the logger currently in use.
     *
     * @return LoggerInterface
     */
    public function getLogger(): LoggerInterface
    {
        if ($this->logger === null) {
            $this->logger = new NullLogger;
        }

        return $this->logger;
    }
}
