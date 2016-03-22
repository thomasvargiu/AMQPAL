<?php

namespace AMQPAL\Adapter\PhpAmqpLib\Factory;

use PhpAmqpLib\Connection\AMQPLazyConnection;
use AMQPAL\Adapter\PhpAmqpLib\Options\ConnectionOptions;

/**
 * Class LazyConnectionFactory
 *
 * @package AMQPAL\Adapter\PhpAmqpLib\Factory
 */
class LazyConnectionFactory implements ConnectionFactoryInterface
{
    /**
     * @codeCoverageIgnore
     *
     * @param ConnectionOptions $options
     *
     * @return AMQPLazyConnection
     */
    public function createConnection(ConnectionOptions $options)
    {
        return new AMQPLazyConnection(
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
