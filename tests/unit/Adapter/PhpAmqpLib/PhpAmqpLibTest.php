<?php

namespace AMQPAL\Adapter\PhpAmqpLib;

use PhpAmqpLib\Channel\AMQPChannel;
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

    public function testCreateChannelWithResource()
    {
        $connection = $this->prophesize(Connection::class);
        $channelPrototype = $this->prophesize(Channel::class);
        $channelResource = $this->prophesize(AMQPChannel::class);

        $channelPrototype->setResource($channelResource->reveal())->shouldBeCalled();
        $channelPrototype->setConnection($connection->reveal())->shouldBeCalled();

        $adapter = new PhpAmqpLib($connection->reveal(), $channelPrototype->reveal());

        $channel = $adapter->createChannel($channelResource->reveal());
        static::assertInstanceOf(Channel::class, $channel);
    }

    public function testCreateChannel()
    {
        $adapterConnection = $this->prophesize(AbstractConnection::class);
        $connection = $this->prophesize(Connection::class);
        $channelPrototype = $this->prophesize(Channel::class);
        $channelResource = $this->prophesize(AMQPChannel::class);

        $channelPrototype->setResource($channelResource->reveal());
        $adapterConnection->channel()->shouldBeCalled()->willReturn($channelResource->reveal());
        $connection->isConnected()->shouldBeCalled()->willReturn(true);
        $connection->getResource()->shouldBeCalled()->willReturn($adapterConnection->reveal());

        $adapter = new PhpAmqpLib($connection->reveal(), $channelPrototype->reveal());

        $channel = $adapter->createChannel();
        static::assertInstanceOf(Channel::class, $channel);
    }

    public function testCreateChannelNotConnected()
    {
        $adapterConnection = $this->prophesize(AbstractConnection::class);
        $connection = $this->prophesize(Connection::class);
        $channelPrototype = $this->prophesize(Channel::class);
        $channelResource = $this->prophesize(AMQPChannel::class);

        $channelPrototype->setResource($channelResource->reveal());
        $adapterConnection->channel()->shouldBeCalled()->willReturn($channelResource->reveal());
        $connection->isConnected()->shouldBeCalled()->willReturn(false);
        $connection->connect()->shouldBeCalled();
        $connection->getResource()->shouldBeCalled()->willReturn($adapterConnection->reveal());

        $adapter = new PhpAmqpLib($connection->reveal(), $channelPrototype->reveal());

        $channel = $adapter->createChannel();
        static::assertInstanceOf(Channel::class, $channel);
    }
}
