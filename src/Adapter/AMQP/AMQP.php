<?php

namespace AMQPAL\Adapter\AMQP;

use AMQPAL\Adapter\AMQP\Options\ConnectionOptions;
use AMQPAL\Adapter\AdapterInterface;
use AMQPAL\Adapter\Exception;

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
            $connection = new Connection($connection, $channelPrototype);
        }
        $this->registerConnection($connection);
    }

    /**
     * @param Connection $connection
     */
    public function registerConnection(Connection $connection)
    {
        $this->connection = $connection;
    }

    /**
     * @return Connection
     */
    public function getConnection()
    {
        return $this->connection;
    }
}
