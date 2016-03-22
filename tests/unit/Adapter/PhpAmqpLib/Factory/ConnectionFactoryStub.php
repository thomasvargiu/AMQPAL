<?php

namespace AMQPAL\Adapter\PhpAmqpLib\Factory;

use PhpAmqpLib\Connection\AbstractConnection;
use AMQPAL\Adapter\PhpAmqpLib\Options\ConnectionOptions;

class ConnectionFactoryStub implements ConnectionFactoryInterface
{

    /**
     * @param ConnectionOptions $options
     * @return AbstractConnection
     */
    public function createConnection(ConnectionOptions $options)
    {
        return true;
    }
}
