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
     * Connection constructor.
     *
     * @param AMQPConnection|ConnectionOptions $connection
     * @throws Exception\BadMethodCallException
     * @throws Exception\InvalidArgumentException
     */
    public function __construct($connection)
    {
        if (!$connection instanceof AMQPConnection) {
            $this->setOptions($connection);
            $connection = $this->createResource($this->getOptions());
        }

        $this->setResource($connection);
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
}
