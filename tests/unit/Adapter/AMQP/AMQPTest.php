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

    public function testCreateChannelWithResource()
    {
        $connection = $this->prophesize(Connection::class);
        $channelPrototype = $this->prophesize(Channel::class);
        $channelResource = $this->prophesize(\AMQPChannel::class);

        $channelPrototype->setResource($channelResource->reveal())->shouldBeCalled();
        $channelPrototype->setConnection($connection->reveal())->shouldBeCalled();

        $adapter = new AMQP($connection->reveal(), $channelPrototype->reveal());

        $channel = $adapter->createChannel($channelResource->reveal());
        static::assertInstanceOf(Channel::class, $channel);
    }

    public function testCreateChannel()
    {
        $connection = $this->prophesize(Connection::class);
        $channelPrototype = $this->prophesize(Channel::class);
        $channelResource = $this->prophesize(\AMQPChannel::class);

        $channelPrototype->setResource($channelResource->reveal())->shouldBeCalled();
        $channelPrototype->setConnection($connection->reveal())->shouldBeCalled();

        $connection->isConnected()->shouldBeCalled()->willReturn(true);
        $connection->connect()->shouldNotBeCalled();

        $adapter = static::getMockBuilder(AMQP::class)
            ->setMethods(['createChannelResource'])
            ->setConstructorArgs([$connection->reveal(), $channelPrototype->reveal()])
            ->getMock();

        $adapter->expects(static::once())
            ->method('createChannelResource')
            ->willReturn($channelResource->reveal());

        $channel = $adapter->createChannel();
        static::assertInstanceOf(Channel::class, $channel);
    }

    public function testCreateChannelWithConnect()
    {
        $connection = $this->prophesize(Connection::class);
        $channelPrototype = $this->prophesize(Channel::class);
        $channelResource = $this->prophesize(\AMQPChannel::class);

        $channelPrototype->setResource($channelResource->reveal())->shouldBeCalled();
        $channelPrototype->setConnection($connection->reveal())->shouldBeCalled();

        $connection->isConnected()->shouldBeCalled()->willReturn(false);
        $connection->connect()->shouldBeCalled();

        $adapter = static::getMockBuilder(AMQP::class)
            ->setMethods(['createChannelResource'])
            ->setConstructorArgs([$connection->reveal(), $channelPrototype->reveal()])
            ->getMock();

        $adapter->expects(static::once())
            ->method('createChannelResource')
            ->willReturn($channelResource->reveal());

        $channel = $adapter->createChannel();
        static::assertInstanceOf(Channel::class, $channel);
    }
}
