<?php

namespace AMQPAL\Adapter\PhpAmqpLib\Factory;

use AMQPAL\Adapter\PhpAmqpLib\Options\ConnectionOptions;
use PhpAmqpLib\Connection\AbstractConnection;

/**
 * Interface ConnectionFactoryInterface
 *
 * @package AMQPAL\Adapter\PhpAmqpLib\Factory
 */
interface ConnectionFactoryInterface
{
    /**
     * @param ConnectionOptions $options
     * @return AbstractConnection
     */
    public function createConnection(ConnectionOptions $options);
}
