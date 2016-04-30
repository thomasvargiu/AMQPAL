<?php

namespace AMQPAL\Adapter\AMQP;

use AMQPAL\Adapter\AMQP\Options\ConnectionOptions;

class ConnectionTest extends \PHPUnit_Framework_TestCase
{

    public function testConstructorWithOptions()
    {
        $options = $this->prophesize(ConnectionOptions::class);

        $options->getHost()->willReturn('hostname');
        $options->getPort()->willReturn(3333);
        $options->getVhost()->willReturn('vhost');
        $options->getUsername()->willReturn('login');
        $options->getPassword()->willReturn('password');
        $options->getReadTimeout()->willReturn(2);
        $options->getWriteTimeout()->willReturn(2);
        $options->getConnectTimeout()->willReturn(2);
        $options->getChannelMax()->willReturn(2);
        $options->getFrameMax()->willReturn(2);
        $options->getHeartbeat()->willReturn(2);
        $options->isPersistent()->willReturn(false);

        $connection = new Connection($options->reveal());

        $resource = $connection->getResource();

        static::assertInstanceOf(\AMQPConnection::class, $resource);
        static::assertEquals($resource->getHost(), 'hostname');
        static::assertEquals($resource->getPort(), 3333);
        static::assertEquals($resource->getVhost(), 'vhost');
        static::assertEquals($resource->getLogin(), 'login');
        static::assertEquals($resource->getPassword(), 'password');
        static::assertEquals($resource->getReadTimeout(), 2);
        static::assertEquals($resource->getWriteTimeout(), 2);
    }

    public function testSetOptionsWithArray()
    {
        $adapterConnection = $this->prophesize(\AMQPConnection::class);

        $connection = new Connection($adapterConnection->reveal());

        $connection->setOptions(['host' => 'foo']);
        $options = $connection->getOptions();
        static::assertInstanceOf(ConnectionOptions::class, $options);
        static::assertEquals('foo', $options->getHost());
    }

    public function testIsConnected()
    {
        $adapterConnection = $this->prophesize(\AMQPConnection::class);
        $adapterConnection->isConnected()->shouldBeCalled()->willReturn(true);

        $connection = new Connection($adapterConnection->reveal());

        $result = $connection->isConnected();
        static::assertTrue($result);
    }

    public function testConnect()
    {
        $adapterConnection = $this->prophesize(\AMQPConnection::class);
        $options = $this->prophesize(ConnectionOptions::class);
        $options->isPersistent()->willReturn(false);

        $adapterConnection->connect()->shouldBeCalled();

        $connection = new Connection($adapterConnection->reveal());
        $connection->setOptions($options->reveal());

        $result = $connection->connect();
        static::assertInstanceOf(Connection::class, $result);
    }

    public function testConnectPersistent()
    {
        $adapterConnection = $this->prophesize(\AMQPConnection::class);
        $options = $this->prophesize(ConnectionOptions::class);
        $options->isPersistent()->willReturn(true);

        $adapterConnection->pconnect()->shouldBeCalled();

        $connection = new Connection($adapterConnection->reveal());
        $connection->setOptions($options->reveal());

        $result = $connection->connect();
        static::assertInstanceOf(Connection::class, $result);
    }

    public function testReconnect()
    {
        $adapterConnection = $this->prophesize(\AMQPConnection::class);
        $options = $this->prophesize(ConnectionOptions::class);
        $options->isPersistent()->willReturn(false);

        $adapterConnection->reconnect()->shouldBeCalled();

        $connection = new Connection($adapterConnection->reveal());
        $connection->setOptions($options->reveal());

        $result = $connection->reconnect();
        static::assertInstanceOf(Connection::class, $result);
    }

    public function testReconnectPersistent()
    {
        $adapterConnection = $this->prophesize(\AMQPConnection::class);
        $options = $this->prophesize(ConnectionOptions::class);
        $options->isPersistent()->willReturn(true);

        $adapterConnection->preconnect()->shouldBeCalled();

        $connection = new Connection($adapterConnection->reveal());
        $connection->setOptions($options->reveal());

        $result = $connection->reconnect();
        static::assertInstanceOf(Connection::class, $result);
    }

    public function testDisconnect()
    {
        $adapterConnection = $this->prophesize(\AMQPConnection::class);
        $options = $this->prophesize(ConnectionOptions::class);
        $options->isPersistent()->willReturn(false);

        $adapterConnection->disconnect()->shouldBeCalled();

        $connection = new Connection($adapterConnection->reveal());
        $connection->setOptions($options->reveal());

        $result = $connection->disconnect();
        static::assertInstanceOf(Connection::class, $result);
    }

    public function testDisconnectPersistent()
    {
        $adapterConnection = $this->prophesize(\AMQPConnection::class);
        $options = $this->prophesize(ConnectionOptions::class);
        $options->isPersistent()->willReturn(true);

        $adapterConnection->pdisconnect()->shouldBeCalled();

        $connection = new Connection($adapterConnection->reveal());
        $connection->setOptions($options->reveal());

        $result = $connection->disconnect();
        static::assertInstanceOf(Connection::class, $result);
    }

    public function testCreateChannelWithResource()
    {
        $adapterConnection = $this->prophesize(\AMQPConnection::class);
        $channelPrototype = $this->prophesize(Channel::class);
        $channelResource = $this->prophesize(\AMQPChannel::class);

        $connection = new Connection($adapterConnection->reveal());
        $connection->registerChannel($channelPrototype->reveal());

        $channelPrototype->setResource($channelResource->reveal())->shouldBeCalled();
        $channelPrototype->setConnection($connection)->shouldBeCalled();

        $result = $connection->createChannel($channelResource->reveal());
        static::assertInstanceOf(Channel::class, $result);
    }

    public function testCreateChannel()
    {
        $adapterConnection = $this->prophesize(\AMQPConnection::class);
        $channelPrototype = $this->prophesize(Channel::class);
        $channelResource = $this->prophesize(\AMQPChannel::class);

        $adapterConnection->isConnected()->shouldBeCalled()->willReturn(true);
        $channelPrototype->setResource($channelResource->reveal())->shouldBeCalled();

        $connection = static::getMockBuilder(Connection::class)
            ->setMethods(['createChannelResource'])
            ->setConstructorArgs([$adapterConnection->reveal(), $channelPrototype->reveal()])
            ->getMock();

        $connection->registerChannel($channelPrototype->reveal());
        $channelPrototype->setConnection($connection)->shouldBeCalled();

        $connection->expects(static::once())
            ->method('createChannelResource')
            ->willReturn($channelResource->reveal());

        $channel = $connection->createChannel();
        static::assertInstanceOf(Channel::class, $channel);
    }

    public function testCreateChannelWithConnect()
    {
        $adapterConnection = $this->prophesize(\AMQPConnection::class);
        $channelPrototype = $this->prophesize(Channel::class);
        $channelResource = $this->prophesize(\AMQPChannel::class);

        $adapterConnection->isConnected()->shouldBeCalled()->willReturn(false);
        $channelPrototype->setResource($channelResource->reveal())->shouldBeCalled();

        $connection = static::getMockBuilder(Connection::class)
            ->setMethods(['createChannelResource', 'connect'])
            ->setConstructorArgs([$adapterConnection->reveal(), $channelPrototype->reveal()])
            ->getMock();

        $connection->registerChannel($channelPrototype->reveal());
        $channelPrototype->setConnection($connection)->shouldBeCalled();

        $connection->expects(static::once())
            ->method('connect')
            ->willReturn($connection);

        $connection->expects(static::once())
            ->method('createChannelResource')
            ->willReturn($channelResource->reveal());

        $channel = $connection->createChannel();
        static::assertInstanceOf(Channel::class, $channel);
    }
}
