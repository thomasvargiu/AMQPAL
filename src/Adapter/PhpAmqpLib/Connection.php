<?php

namespace AMQPAL\Adapter\PhpAmqpLib;

use AMQPAL\Adapter\Exception;
use AMQPAL\Adapter\ConnectionInterface;
use AMQPAL\Adapter\PhpAmqpLib\Options\ConnectionOptions;
use PhpAmqpLib\Connection\AbstractConnection;
use Traversable;

/**
 * Class Connection
 *
 * @package AMQPAL\Adapter\PhpAmqpLib
 */
class Connection implements ConnectionInterface
{
    /**
     * @var AbstractConnection
     */
    protected $resource;
    /**
     * @var ConnectionOptions
     */
    protected $options;

    /**
     * Connection constructor.
     *
     * @param AbstractConnection|ConnectionOptions $connection
     * @throws Exception\RuntimeException
     * @throws Exception\InvalidArgumentException
     * @throws Exception\BadMethodCallException
     */
    public function __construct($connection)
    {
        if (!$connection instanceof AbstractConnection) {
            $this->setOptions($connection);
            $connection = $this->createResource();
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
     * @return AbstractConnection
     * @throws Exception\RuntimeException
     * @throws Exception\InvalidArgumentException
     */
    protected function createResource()
    {
        $factory = $this->getOptions()
            ->getConnectionFactoryFactory()
            ->createFactory($this->getOptions()->getType());
        return $factory->createConnection($this->getOptions());
    }

    /**
     * @param AbstractConnection $resource
     * @return $this
     */
    public function setResource(AbstractConnection $resource)
    {
        $this->resource = $resource;
        return $this;
    }

    /**
     * @return AbstractConnection
     */
    public function getResource()
    {
        return $this->resource;
    }

    /**
     * Establish a connection with the AMQP broker.
     *
     * @return $this
     * @throws Exception\RuntimeException
     * @throws Exception\InvalidArgumentException
     */
    public function connect()
    {
        if (!$this->resource->isConnected()) {
            $this->resource->reconnect();
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
        $this->resource->reconnect();

        return $this;
    }

    /**
     * Closes the connection with the AMQP broker.
     *
     * @return $this
     * @throws Exception\RuntimeException
     */
    public function disconnect()
    {
        $this->resource->close();

        return $this;
    }

    /**
     * Check whether the connection to the AMQP broker is still valid.
     *
     * @return bool
     * @throws Exception\RuntimeException
     */
    public function isConnected()
    {
        return $this->resource->isConnected();
    }
}
