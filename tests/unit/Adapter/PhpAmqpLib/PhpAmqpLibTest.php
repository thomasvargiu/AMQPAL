<?php

namespace AMQPAL\Adapter\PhpAmqpLib;

use PhpAmqpLib\Connection\AbstractConnection;
use AMQPAL\Options;
use Prophecy\Argument;

class PhpAmqpLibTest extends \PHPUnit_Framework_TestCase
{
    public function testConstructorWithConnectionResource()
    {
        $adapterConnection = $this->prophesize(AbstractConnection::class);
        $adapter = new PhpAmqpLib($adapterConnection->reveal());

        static::assertInstanceOf(Connection::class, $adapter->getConnection());
    }
}
