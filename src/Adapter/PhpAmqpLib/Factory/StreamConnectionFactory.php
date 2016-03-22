<?php

namespace AMQPAL\Adapter\PhpAmqpLib\Factory;

use AMQPAL\Adapter\PhpAmqpLib\Options\ConnectionOptions;
use PhpAmqpLib\Connection\AMQPStreamConnection;

/**
 * Class StreamConnectionFactory
 *
 * @package AMQPAL\Adapter\PhpAmqpLib\Factory
 */
class StreamConnectionFactory implements ConnectionFactoryInterface
{
    /**
     * @codeCoverageIgnore
     *
     * @param ConnectionOptions $options
     *
     * @return AMQPStreamConnection
     */
    public function createConnection(ConnectionOptions $options)
    {
        return new AMQPStreamConnection(
            $options->getHost(),
            $options->getPort(),
            $options->getUsername(),
            $options->getPassword(),
            $options->getVhost(),
            $options->isInsist(),
            $options->getLoginMethod(),
            null,
            $options->getLocale(),
            $options->getConnectionTimeout(),
            $options->getReadWriteTimeout(),
            null,
            $options->isKeepAlive(),
            $options->getHeartbeat()
        );
    }
}
