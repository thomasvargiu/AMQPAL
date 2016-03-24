<?php

namespace AMQPAL\Adapter\PhpAmqpLib;

use AMQPAL\Adapter\ChannelInterface;
use AMQPAL\Adapter\ConnectionInterface;
use AMQPAL\Adapter\ExchangeInterface;
use AMQPAL\Adapter\QueueInterface;
use AMQPAL\Exception;
use AMQPAL\Options;
use PhpAmqpLib\Channel\AMQPChannel;

/**
 * Class Channel
 *
 * @package AMQPAL\Adapter\PhpAmqpLib
 */
class Channel implements ChannelInterface
{

    /**
     * @var AMQPChannel
     */
    protected $resource;
    /**
     * @var ConnectionInterface
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
     * Check the channel connection.
     *
     * @return bool Indicates whether the channel is connected.
     */
    public function isConnected()
    {
        return $this->getConnection()->isConnected();
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
     * @param Connection $connection
     * @return $this
     */
    public function setConnection(Connection $connection)
    {
        $this->connection = $connection;
        return $this;
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
     * @return AMQPChannel
     */
    public function getResource()
    {
        return $this->resource;
    }

    /**
     * @param AMQPChannel $resource
     * @return $this
     */
    public function setResource(AMQPChannel $resource)
    {
        $this->resource = $resource;

        return $this;
    }

    /**
     * Set the window size and the number of messages to prefetch from the broker.
     *
     * @param int $prefetchSize  The window size, in octets, to prefetch.
     * @param int $prefetchCount The number of messages to prefetch.
     * @return $this
     */
    public function setQos($prefetchSize, $prefetchCount)
    {
        $this->getResource()->basic_qos($prefetchSize, $prefetchCount, false);

        return $this;
    }

    /**
     * Start a transaction.
     *
     * @return $this
     */
    public function startTransaction()
    {
        $this->getResource()->tx_select();

        return $this;
    }

    /**
     * Commit a pending transaction.
     *
     * @return $this
     */
    public function commitTransaction()
    {
        $this->getResource()->tx_commit();

        return $this;
    }

    /**
     * Rollback a transaction.
     *
     * @return $this
     */
    public function rollbackTransaction()
    {
        $this->getResource()->tx_rollback();

        return $this;
    }

    /**
     * Redeliver unacknowledged messages.
     *
     * @param bool $requeue
     * @return $this
     */
    public function basicRecover($requeue = true)
    {
        $this->getResource()->basic_recover($requeue);

        return $this;
    }

    /**
     * Create a new queue
     *
     * @param Options\QueueOptions|\Traversable|array $options
     * @return QueueInterface
     * @throws Exception\BadMethodCallException
     * @throws Exception\InvalidArgumentException
     */
    public function createQueue($options)
    {
        $queue = clone $this->queuePrototype;

        if (!$options instanceof Options\QueueOptions) {
            $options = new Options\QueueOptions($options);
        }

        $queue->setChannel($this);
        $queue->setOptions($options);

        return $queue;
    }

    /**
     * Create a new exchange
     *
     * @param Options\ExchangeOptions|\Traversable|array $options
     * @return ExchangeInterface
     * @throws Exception\BadMethodCallException
     * @throws Exception\InvalidArgumentException
     */
    public function createExchange($options)
    {
        $exchange = clone $this->exchangePrototype;

        if (!$options instanceof Options\ExchangeOptions) {
            $options = new Options\ExchangeOptions($options);
        }

        $exchange->setChannel($this);
        $exchange->setOptions($options);

        return $exchange;
    }
}
