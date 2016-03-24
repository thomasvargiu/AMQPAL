<?php

namespace AMQPAL\Adapter;

use AMQPAL\Options;

/**
 * Class ChannelInterface
 *
 * @package AMQPAL\Adapter
 */
interface ChannelInterface
{
    /**
     * Check the channel connection.
     *
     * @return bool Indicates whether the channel is connected.
     */
    public function isConnected();

    /**
     * Return internal channel ID
     *
     * @return integer
     */
    public function getChannelId();

    /**
     * Set the window size and the number of messages to prefetch from the broker.
     *
     * @param int $prefetchSize  The window size, in octets, to prefetch.
     * @param int $prefetchCount The number of messages to prefetch.
     * @return $this
     */
    public function setQos($prefetchSize, $prefetchCount);

    /**
     * Start a transaction.
     *
     * @return $this
     */
    public function startTransaction();

    /**
     * Commit a pending transaction.
     *
     * @return $this
     */
    public function commitTransaction();

    /**
     * Rollback a transaction.
     *
     * @return $this
     */
    public function rollbackTransaction();

    /**
     * Get the connection object in use
     *
     * @return ConnectionInterface
     */
    public function getConnection();

    /**
     * Redeliver unacknowledged messages.
     *
     * @param bool $requeue
     * @return $this
     */
    public function basicRecover($requeue = true);

    /**
     * Create a new queue
     *
     * @param Options\QueueOptions|\Traversable|array $options
     * @return QueueInterface
     */
    public function createQueue($options);

    /**
     * Create a new exchange
     *
     * @param Options\ExchangeOptions|\Traversable|array $options
     * @return ExchangeInterface
     */
    public function createExchange($options);
}
