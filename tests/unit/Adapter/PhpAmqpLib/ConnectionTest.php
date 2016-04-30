<?php

namespace AMQPAL\Adapter\PhpAmqpLib;

use AMQPAL\Adapter\PhpAmqpLib\Options\ConnectionOptions;
use PhpAmqpLib\Connection\AbstractConnection;
use PhpAmqpLib\Channel\AMQPChannel;
use AMQPAL\Options;
use Prophecy\Argument;

class ConnectionTest extends \PHPUnit_Framework_TestCase
{

    public function testConstructorWithOptions()
    {
        $adapterConnection = $this->prophesize(AbstractConnection::class);
        $options = $this->prophesize(ConnectionOptions::class);
        $connectionFactoryFactory = $this->prophesize(Factory\ConnectionFactoryFactory::class);
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
        $adapterConnection = $this->prophesize(AbstractConnection::class);
        $options = $this->prophesize(ConnectionOptions::class);

        $connection = new Connection($adapterConnection->reveal());

        static::assertSame($connection, $connection->setOptions($options->reveal()));
    }

    public function testSetOptionsArray()
    {
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
        $adapterConnection = $this->prophesize(AbstractConnection::class);
        $adapterConnection->isConnected()->shouldBeCalled()->willReturn(true);

        $connection = new Connection($adapterConnection->reveal());

        $result = $connection->isConnected();
        static::assertTrue($result);
    }

    public function testConnect()
    {
        $adapterConnection = $this->prophesize(AbstractConnection::class);

        $adapterConnection->isConnected()->shouldBeCalled()->willReturn(false);
        $adapterConnection->reconnect()->shouldBeCalled();

        $connection = new Connection($adapterConnection->reveal());

        $result = $connection->connect();
        static::assertInstanceOf(Connection::class, $result);
    }

    public function testReconnect()
    {
        $adapterConnection = $this->prophesize(AbstractConnection::class);

        $adapterConnection->reconnect()->shouldBeCalled();

        $connection = new Connection($adapterConnection->reveal());

        $result = $connection->reconnect();
        static::assertInstanceOf(Connection::class, $result);
    }

    public function testDisconnect()
    {
        $adapterConnection = $this->prophesize(AbstractConnection::class);

        $adapterConnection->close()->shouldBeCalled();

        $connection = new Connection($adapterConnection->reveal());

        $result = $connection->disconnect();
        static::assertInstanceOf(Connection::class, $result);
    }

    public function testCreateChannelWithResource()
    {
        $adapterConnection = $this->prophesize(AbstractConnection::class);
        $channelPrototype = $this->prophesize(Channel::class);
        $channelResource = $this->prophesize(AMQPChannel::class);

        $connection = new Connection($adapterConnection->reveal());
        $connection->registerChannel($channelPrototype->reveal());

        $channelPrototype->setResource($channelResource->reveal())->shouldBeCalled();
        $channelPrototype->setConnection($connection)->shouldBeCalled();

        $result = $connection->createChannel($channelResource->reveal());
        static::assertInstanceOf(Channel::class, $result);
    }

    public function testCreateChannel()
    {
        $adapterConnection = $this->prophesize(AbstractConnection::class);
        $channelPrototype = $this->prophesize(Channel::class);
        $channelResource = $this->prophesize(AMQPChannel::class);

        $adapterConnection->isConnected()->shouldBeCalled()->willReturn(true);
        $adapterConnection->channel()->shouldBeCalled()->willReturn($channelResource->reveal());
        $channelPrototype->setResource($channelResource->reveal())->shouldBeCalled();

        $connection = new Connection($adapterConnection->reveal());
        $connection->registerChannel($channelPrototype->reveal());
        $channelPrototype->setConnection($connection)->shouldBeCalled();

        $channel = $connection->createChannel();
        static::assertInstanceOf(Channel::class, $channel);
    }

    public function testCreateChannelWithConnect()
    {
        $adapterConnection = $this->prophesize(AbstractConnection::class);
        $channelPrototype = $this->prophesize(Channel::class);
        $channelResource = $this->prophesize(AMQPChannel::class);

        $adapterConnection->isConnected()->shouldBeCalled()->willReturn(false);
        $adapterConnection->reconnect()->shouldBeCalled();
        $adapterConnection->channel()->shouldBeCalled()->willReturn($channelResource->reveal());
        $channelPrototype->setResource($channelResource->reveal())->shouldBeCalled();

        $connection = new Connection($adapterConnection->reveal());
        $connection->registerChannel($channelPrototype->reveal());
        $channelPrototype->setConnection($connection)->shouldBeCalled();
        $channelPrototype->setConnection($connection)->shouldBeCalled();

        $channel = $connection->createChannel();
        static::assertInstanceOf(Channel::class, $channel);
    }
}
