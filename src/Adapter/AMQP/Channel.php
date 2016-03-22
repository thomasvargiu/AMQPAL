<?php

namespace AMQPAL\Adapter\AMQP;

use AMQPAL\Adapter\ChannelInterface;
use AMQPAL\Options;

/**
 * Class Channel
 *
 * @package AMQPAL\Adapter\AMQP
 */
class Channel implements ChannelInterface
{
    /**
     * @var \AMQPChannel
     */
    protected $resource;
    /**
     * @var Connection
     */
    protected $connection;
    /**
     * @var Exchange
     */
    protected $exchangePrototype;
    /**
     * @var Queue
     */
    protected $queuePrototype;

    /**
     * Channel constructor.
     *
     * @param Exchange $exchangePrototype
     * @param Queue    $queuePrototype
     */
    public function __construct(Exchange $exchangePrototype = null, Queue $queuePrototype = null)
    {
        $this->registerExchange($exchangePrototype ?: new Exchange());
        $this->registerQueue($queuePrototype ?: new Queue());
    }

    /**
     * @param Exchange $exchange
     */
    public function registerExchange(Exchange $exchange)
    {
        $this->exchangePrototype = $exchange;
    }

    /**
     * @param Queue $queue
     */
    public function registerQueue(Queue $queue)
    {
        $this->queuePrototype = $queue;
    }

    /**
     * @return \AMQPChannel
     */
    public function getResource()
    {
        return $this->resource;
    }

    /**
     * @param \AMQPChannel $resource
     * @return $this
     */
    public function setResource(\AMQPChannel $resource)
    {
        $this->resource = $resource;

        return $this;
    }

    /**
     * Check the channel connection.
     *
     * @return bool Indicates whether the channel is connected.
     */
    public function isConnected()
    {
        return $this->getResource()->isConnected();
    }

    /**
     * Return internal channel ID
     *
     * @return integer
     */
    public function getChannelId()
    {
        return $this->getResource()->getChannelId();
    }

    /**
     * Set the window size and the number of messages to prefetch from the broker.
     *
     * @param int $prefetchSize  The window size, in octets, to prefetch.
     * @param int $prefetchCount The number of messages to prefetch.
     * @return $this
     * @throws \AMQPConnectionException
     */
    public function setQos($prefetchSize, $prefetchCount)
    {
        $this->getResource()->qos($prefetchSize, $prefetchCount);

        return $this;
    }

    /**
     * Start a transaction.
     *
     * @return $this
     * @throws \AMQPChannelException
     * @throws \AMQPConnectionException
     */
    public function startTransaction()
    {
        $this->getResource()->startTransaction();

        return $this;
    }

    /**
     * Commit a pending transaction.
     *
     * @return $this
     * @throws \AMQPChannelException
     * @throws \AMQPConnectionException
     */
    public function commitTransaction()
    {
        $this->getResource()->commitTransaction();

        return $this;
    }

    /**
     * Rollback a transaction.
     *
     * @return $this
     * @throws \AMQPChannelException
     * @throws \AMQPConnectionException
     */
    public function rollbackTransaction()
    {
        $this->getResource()->rollbackTransaction();

        return $this;
    }

    /**
     * @param Connection $connection
     * @return $this
     */
    public function setConnection(Connection $connection)
    {
        $this->connection = $connection;
        return $this;
    }

    /**
     * Get the connection object in use
     *
     * @return Connection
     */
    public function getConnection()
    {
        return $this->connection;
    }

    /**
     * Redeliver unacknowledged messages.
     *
     * @param bool $requeue
     * @return $this
     */
    public function basicRecover($requeue = true)
    {
        $this->getResource()->basicRecover($requeue);

        return $this;
    }

    /**
     * @param Options\QueueOptions $options
     * @param \AMQPQueue           $resource
     * @return Queue
     * @throws \AMQPConnectionException
     * @throws \AMQPQueueException
     */
    public function createQueue(Options\QueueOptions $options, $resource = null)
    {
        $queue = clone $this->queuePrototype;

        if ($resource instanceof \AMQPQueue) {
            $queue->setResource($resource);
        } else {
            $queue->setResource($this->createQueueResource());
        }

        $queue->setChannel($this);
        $queue->setOptions($options);

        return $queue;
    }

    /**
     * @param Options\ExchangeOptions $options
     * @param \AMQPExchange           $resource
     * @return Exchange
     * @throws \AMQPConnectionException
     * @throws \AMQPExchangeException
     */
    public function createExchange(Options\ExchangeOptions $options, $resource = null)
    {
        $exchange = clone $this->exchangePrototype;

        if ($resource instanceof \AMQPExchange) {
            $exchange->setResource($resource);
        } else {
            $exchange->setResource($this->createExchangeResource());
        }

        $exchange->setChannel($this);
        $exchange->setOptions($options);

        return $exchange;
    }

    /**
     * @return \AMQPQueue
     * @throws \AMQPConnectionException
     * @throws \AMQPQueueException
     * @codeCoverageIgnore
     */
    protected function createQueueResource()
    {
        return new \AMQPQueue($this->getResource());
    }

    /**
     * @return \AMQPExchange
     * @throws \AMQPConnectionException
     * @throws \AMQPExchangeException
     * @codeCoverageIgnore
     */
    protected function createExchangeResource()
    {
        return new \AMQPExchange($this->getResource());
    }
}
