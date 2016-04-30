<?php

namespace AMQPAL\Adapter\AMQP;

use AMQPAL\Options;
use Prophecy\Argument;

class AMQPTest extends \PHPUnit_Framework_TestCase
{
    public function testConstructorWithConnectionResource()
    {
        $adapterConnection = $this->prophesize(\AMQPConnection::class);
        $adapter = new AMQP($adapterConnection->reveal());

        static::assertInstanceOf(Connection::class, $adapter->getConnection());
    }
}
