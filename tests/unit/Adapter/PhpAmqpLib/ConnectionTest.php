<?php

namespace AMQPAL\Adapter\PhpAmqpLib;

use AMQPAL\Adapter\PhpAmqpLib\Options\ConnectionOptions;
use PhpAmqpLib\Connection\AbstractConnection;

class ConnectionTest extends \PHPUnit_Framework_TestCase
{

    public function testConstructorWithOptions()
    {
        /** @var AbstractConnection|\Prophecy\Prophecy\ObjectProphecy $adapterConnection */
        $adapterConnection = $this->prophesize(AbstractConnection::class);
        /** @var ConnectionOptions|\Prophecy\Prophecy\ObjectProphecy $options */
        $options = $this->prophesize(ConnectionOptions::class);
        /** @var Factory\ConnectionFactoryFactory|\Prophecy\Prophecy\ObjectProphecy $connectionFactoryFactory */
        $connectionFactoryFactory = $this->prophesize(Factory\ConnectionFactoryFactory::class);
        /** @var Factory\ConnectionFactoryInterface|\Prophecy\Prophecy\ObjectProphecy $connectionFactory */
        $connectionFactory = $this->prophesize(Factory\ConnectionFactoryInterface::class);

        $connectionFactory->createConnection($options->reveal())->willReturn($adapterConnection->reveal());
        $connectionFactoryFactory->createFactory('foo')->willReturn($connectionFactory->reveal());
        $options->getType()->shouldBeCalled()->willReturn('foo');
        $options->getConnectionFactoryFactory()->willReturn($connectionFactoryFactory->reveal());

        $connection = new Connection($options->reveal());

        $resource = $connection->getResource();

        static::assertInstanceOf(AbstractConnection::class, $resource);
        static::assertSame($adapterConnection->reveal(), $resource);
    }

    public function testSetOptions()
    {
        /** @var AbstractConnection|\Prophecy\Prophecy\ObjectProphecy $adapterConnection */
        $adapterConnection = $this->prophesize(AbstractConnection::class);
        /** @var ConnectionOptions|\Prophecy\Prophecy\ObjectProphecy $options */
        $options = $this->prophesize(ConnectionOptions::class);

        $connection = new Connection($adapterConnection->reveal());

        static::assertSame($connection, $connection->setOptions($options->reveal()));
    }

    public function testSetOptionsArray()
    {
        /** @var AbstractConnection|\Prophecy\Prophecy\ObjectProphecy $adapterConnection */
        $adapterConnection = $this->prophesize(AbstractConnection::class);

        $options = [
            'type' => 'foo'
        ];

        $connection = new Connection($adapterConnection->reveal());

        static::assertSame($connection, $connection->setOptions($options));
        $optionsResult = $connection->getOptions();
        static::assertInstanceOf(ConnectionOptions::class, $optionsResult);
        static::assertEquals('foo', $optionsResult->getType());
    }

    public function testIsConnected()
    {
        /** @var AbstractConnection|\Prophecy\Prophecy\ObjectProphecy $adapterConnection */
        $adapterConnection = $this->prophesize(AbstractConnection::class);
        $adapterConnection->isConnected()->shouldBeCalled()->willReturn(true);

        $connection = new Connection($adapterConnection->reveal());

        $result = $connection->isConnected();
        static::assertTrue($result);
    }

    public function testConnect()
    {
        /** @var AbstractConnection|\Prophecy\Prophecy\ObjectProphecy $adapterConnection */
        $adapterConnection = $this->prophesize(AbstractConnection::class);

        $adapterConnection->isConnected()->shouldBeCalled()->willReturn(false);
        $adapterConnection->reconnect()->shouldBeCalled();

        $connection = new Connection($adapterConnection->reveal());

        $result = $connection->connect();
        static::assertInstanceOf(Connection::class, $result);
    }

    public function testReconnect()
    {
        /** @var AbstractConnection|\Prophecy\Prophecy\ObjectProphecy $adapterConnection */
        $adapterConnection = $this->prophesize(AbstractConnection::class);

        $adapterConnection->reconnect()->shouldBeCalled();

        $connection = new Connection($adapterConnection->reveal());

        $result = $connection->reconnect();
        static::assertInstanceOf(Connection::class, $result);
    }

    public function testDisconnect()
    {
        /** @var AbstractConnection|\Prophecy\Prophecy\ObjectProphecy $adapterConnection */
        $adapterConnection = $this->prophesize(AbstractConnection::class);

        $adapterConnection->close()->shouldBeCalled();

        $connection = new Connection($adapterConnection->reveal());

        $result = $connection->disconnect();
        static::assertInstanceOf(Connection::class, $result);
    }
}
