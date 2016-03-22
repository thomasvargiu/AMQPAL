<?php

namespace AMQPAL\Adapter\AMQP;

use AMQPAL\Adapter\AMQP\Options\ConnectionOptions;
use AMQPAL\Adapter\AdapterInterface;
use AMQPAL\Adapter\Exception;
use AMQPAL\Options;

/**
 * Class AMQP
 *
 * @package AMQPAL\Adapter\AMQP
 */
class AMQP implements AdapterInterface
{
    /**
     * @var Connection
     */
    protected $connection;
    /**
     * @var Channel
     */
    protected $channelPrototype;

    /**
     * AMQP constructor.
     *
     * @param Connection|ConnectionOptions|array|\Traversable|\AMQPConnection $connection
     * @param Channel    $channelPrototype
     * @throws Exception\InvalidArgumentException
     * @throws Exception\BadMethodCallException
     */
    public function __construct($connection, Channel $channelPrototype = null)
    {
        if (!$connection instanceof Connection) {
            $connection = new Connection($connection);
        }
        $this->registerConnection($connection);
        $this->registerChannel($channelPrototype ?: new Channel());
    }

    /**
     * @param Connection $connection
     */
    public function registerConnection(Connection $connection)
    {
        $this->connection = $connection;
    }

    /**
     * @param Channel $channel
     */
    public function registerChannel(Channel $channel)
    {
        $this->channelPrototype = $channel;
    }

    /**
     * @return Connection
     */
    public function getConnection()
    {
        return $this->connection;
    }

    /**
     * @param \AMQPChannel $resource
     * @return Channel
     * @throws \AMQPConnectionException
     */
    public function createChannel($resource = null)
    {
        $channel = clone $this->channelPrototype;

        $channel->setConnection($this->getConnection());

        if ($resource instanceof \AMQPChannel) {
            $channel->setResource($resource);
        } else {
            if (!$this->getConnection()->isConnected()) {
                $this->getConnection()->connect();
            }
            $channel->setResource($this->createChannelResource());
        }

        return $channel;
    }

    /**
     * @return \AMQPChannel
     * @throws \AMQPConnectionException
     * @codeCoverageIgnore
     */
    protected function createChannelResource()
    {
        return new \AMQPChannel($this->connection->getResource());
    }
}
