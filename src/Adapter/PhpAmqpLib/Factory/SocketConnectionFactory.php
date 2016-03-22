<?php

namespace AMQPAL\Adapter\PhpAmqpLib\Factory;

use AMQPAL\Adapter\PhpAmqpLib\Options\ConnectionOptions;
use PhpAmqpLib\Connection\AMQPSocketConnection;

/**
 * Class SocketConnectionFactory
 *
 * @package AMQPAL\Adapter\PhpAmqpLib\Factory
 */
class SocketConnectionFactory implements ConnectionFactoryInterface
{
    /**
     * @codeCoverageIgnore
     *
     * @param ConnectionOptions $options
     *
     * @return AMQPSocketConnection
     */
    public function createConnection(ConnectionOptions $options)
    {
        return new AMQPSocketConnection(
            $options->getHost(),
            $options->getPort(),
            $options->getUsername(),
            $options->getPassword(),
            $options->getVhost(),
            $options->isInsist(),
            $options->getLoginMethod(),
            null,
            $options->getLocale(),
            $options->getReadWriteTimeout(),
            $options->isKeepAlive()
        );
    }
}
