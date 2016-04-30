<?php

namespace AMQPAL\Adapter\AMQP;

use Traversable;
use AMQPConnection;
use AMQPAL\Adapter\Exception;
use AMQPAL\Adapter\ConnectionInterface;
use AMQPAL\Adapter\AMQP\Options\ConnectionOptions;

/**
 * Class Connection
 *
 * @package AMQPAL\Adapter\AMQP
 */
class Connection implements ConnectionInterface
{
    /**
     * @var AMQPConnection
     */
    protected $resource;
    /**
     * @var ConnectionOptions
     */
    protected $options;
    /**
     * @var Channel
     */
    protected $channelPrototype;

    /**
     * Connection constructor.
     *
     * @param AMQPConnection|ConnectionOptions $connection
     * @param Channel $channelPrototype
     * @throws Exception\BadMethodCallException
     * @throws Exception\InvalidArgumentException
     */
    public function __construct($connection, Channel $channelPrototype = null)
    {
        if (!$connection instanceof AMQPConnection) {
            $this->setOptions($connection);
            $connection = $this->createResource($this->getOptions());
        }

        $this->setResource($connection);
        $this->registerChannel($channelPrototype ?: new Channel());
    }

    /**
     * @return ConnectionOptions
     */
    public function getOptions()
    {
        return $this->options;
    }

    /**
     * @param ConnectionOptions|Traversable|array $options
     * @return $this
     * @throws Exception\InvalidArgumentException
     * @throws Exception\BadMethodCallException
     */
    public function setOptions($options)
    {
        if (!$options instanceof ConnectionOptions) {
            $options = new ConnectionOptions($options);
        }
        $this->options = $options;

        return $this;
    }

    /**
     * @param ConnectionOptions $options
     * @return AMQPConnection
     */
    protected function createResource(ConnectionOptions $options)
    {
        $params = [
            'host'  => $options->getHost(),
            'port'  => $options->getPort(),
            'vhost' => $options->getVhost(),
            'login' => $options->getUsername(),
            'password' => $options->getPassword(),
            'read_timeout'  => $options->getReadTimeout(),
            'write_timeout' => $options->getWriteTimeout(),
            'connect_timeout' => $options->getConnectTimeout(),
            'channel_max' => $options->getChannelMax(),
            'frame_max' => $options->getFrameMax(),
            'heartbeat' => $options->getHeartbeat()
        ];

        return new AMQPConnection(array_filter($params, [$this, 'filterConnectionParam']));
    }

    /**
     * @param mixed $paramValue
     * @return bool
     */
    protected function filterConnectionParam($paramValue)
    {
        return null !== $paramValue;
    }

    /**
     * @param AMQPConnection $resource
     * @return $this
     */
    public function setResource(AMQPConnection $resource)
    {
        $this->resource = $resource;
        return $this;
    }

    /**
     * @return AMQPConnection
     */
    public function getResource()
    {
        return $this->resource;
    }

    /**
     * Establish a connection with the AMQP broker.
     *
     * @return $this
     * @throws \AMQPConnectionException
     */
    public function connect()
    {
        if ($this->getOptions()->isPersistent()) {
            $this->getResource()->pconnect();
        } else {
            $this->getResource()->connect();
        }

        return $this;
    }

    /**
     * Close any open connections and initiate a new one with the AMQP broker.
     *
     * @return $this
     */
    public function reconnect()
    {
        if ($this->getOptions()->isPersistent()) {
            $this->getResource()->preconnect();
        } else {
            $this->getResource()->reconnect();
        }

        return $this;
    }

    /**
     * Closes the connection with the AMQP broker.
     *
     * @return $this
     */
    public function disconnect()
    {
        if ($this->getOptions()->isPersistent()) {
            // @todo: should disconnect on persistent connection?
            $this->getResource()->pdisconnect();
        } else {
            $this->getResource()->disconnect();
        }

        return $this;
    }

    /**
     * Check whether the connection to the AMQP broker is still valid.
     *
     * @return bool
     */
    public function isConnected()
    {
        return $this->getResource()->isConnected();
    }

    /**
     * @param \AMQPChannel $resource
     * @return Channel
     * @throws \AMQPConnectionException
     */
    public function createChannel($resource = null)
    {
        $channel = clone $this->channelPrototype;

        $channel->setConnection($this);

        if ($resource instanceof \AMQPChannel) {
            $channel->setResource($resource);
        } else {
            if (!$this->isConnected()) {
                $this->connect();
            }
            $channel->setResource($this->createChannelResource());
        }

        return $channel;
    }

    /**
     * @param Channel $channel
     */
    public function registerChannel(Channel $channel)
    {
        $this->channelPrototype = $channel;
    }

    /**
     * @return \AMQPChannel
     * @throws \AMQPConnectionException
     * @codeCoverageIgnore
     */
    protected function createChannelResource()
    {
        return new \AMQPChannel($this->getResource());
    }
}
