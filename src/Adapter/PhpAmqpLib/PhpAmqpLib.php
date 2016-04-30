<?php

namespace AMQPAL\Adapter\PhpAmqpLib;

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
