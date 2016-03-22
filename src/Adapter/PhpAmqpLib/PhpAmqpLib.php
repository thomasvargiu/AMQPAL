<?php

namespace AMQPAL\Adapter\PhpAmqpLib;

use PhpAmqpLib\Channel\AMQPChannel as LibChannel;
use PhpAmqpLib\Connection\AbstractConnection as LibConnection;
use AMQPAL\Adapter\AdapterInterface;
use AMQPAL\Adapter\Exception;
use AMQPAL\Adapter\PhpAmqpLib\Options\ConnectionOptions;

class PhpAmqpLib implements AdapterInterface
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
     * PhpAmqpLib constructor.
     *
     * @param Connection|ConnectionOptions|array|\Traversable|LibConnection $connection
     * @param Channel                                    $channelPrototype
     * @throws Exception\BadMethodCallException
     * @throws Exception\InvalidArgumentException
     * @throws Exception\RuntimeException
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
     * @param LibChannel $resource
     * @return Channel
     * @throws Exception\RuntimeException
     * @throws Exception\InvalidArgumentException
     */
    public function createChannel($resource = null)
    {
        $channel = clone $this->channelPrototype;

        $channel->setConnection($this->getConnection());

        if ($resource instanceof LibChannel) {
            $channel->setResource($resource);
        } else {
            if (!$this->getConnection()->isConnected()) {
                $this->getConnection()->connect();
            }
            $channel->setResource($this->getConnection()->getResource()->channel());
        }

        return $channel;
    }

    /**
     * @return Connection
     */
    public function getConnection()
    {
        return $this->connection;
    }
}
