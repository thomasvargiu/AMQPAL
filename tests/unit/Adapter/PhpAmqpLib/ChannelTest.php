<?php

namespace AMQPAL\Adapter\PhpAmqpLib;

use PhpAmqpLib\Channel\AMQPChannel;
use Prophecy\Argument;
use AMQPAL\Options\ExchangeOptions;
use AMQPAL\Options\QueueOptions;

class ChannelTest extends \PHPUnit_Framework_TestCase
{
    public function testSetResource()
    {
        $resource = $this->prophesize(AMQPChannel::class);

        $channel = new Channel();
        $channel->setResource($resource->reveal());

        static::assertSame($resource->reveal(), $channel->getResource());
    }

    public function testIsConnected()
    {
        $resource = $this->prophesize(AMQPChannel::class);
        $connection = $this->prophesize(Connection::class);

        $connection->isConnected()->shouldBeCalled()->willReturn(true);

        $channel = new Channel();
        $channel->setConnection($connection->reveal());
        $channel->setResource($resource->reveal());

        static::assertTrue($channel->isConnected());
    }

    public function testGetChannelId()
    {
        $resource = $this->prophesize(AMQPChannel::class);

        $resource->getChannelId()->shouldBeCalled()->willReturn(1234);

        $channel = new Channel();
        $channel->setResource($resource->reveal());

        static::assertEquals(1234, $channel->getChannelId());
    }

    public function testSetQos()
    {
        $resource = $this->prophesize(AMQPChannel::class);

        $resource->basic_qos(2, 3, false)->shouldBeCalled();

        $channel = new Channel();
        $channel->setResource($resource->reveal());

        $channel->setQos(2, 3);
    }

    public function testStartTransaction()
    {
        /** @var \Prophecy\Prophecy\ObjectProphecy|AMQPChannel $resource */
        $resource = $this->prophesize(AMQPChannel::class);

        $resource->tx_select()->shouldBeCalled();

        $channel = new Channel();
        $channel->setResource($resource->reveal());

        $channel->startTransaction();
    }

    public function testCommitTransaction()
    {
        /** @var \Prophecy\Prophecy\ObjectProphecy|AMQPChannel $resource */
        $resource = $this->prophesize(AMQPChannel::class);

        $resource->tx_commit()->shouldBeCalled();

        $channel = new Channel();
        $channel->setResource($resource->reveal());

        $channel->commitTransaction();
    }

    public function testRollbackTransaction()
    {
        /** @var \Prophecy\Prophecy\ObjectProphecy|AMQPChannel $resource */
        $resource = $this->prophesize(AMQPChannel::class);

        $resource->tx_rollback()->shouldBeCalled();

        $channel = new Channel();
        $channel->setResource($resource->reveal());

        $channel->rollbackTransaction();
    }

    public function testBasicRecoverWithDefaults()
    {
        /** @var \Prophecy\Prophecy\ObjectProphecy|AMQPChannel $resource */
        $resource = $this->prophesize(AMQPChannel::class);

        $resource->basic_recover(true)->shouldBeCalled();

        $channel = new Channel();
        $channel->setResource($resource->reveal());

        $channel->basicRecover();
    }

    public function testBasicRecoverWithNoRequeue()
    {
        /** @var \Prophecy\Prophecy\ObjectProphecy|AMQPChannel $resource */
        $resource = $this->prophesize(AMQPChannel::class);

        $resource->basic_recover(false)->shouldBeCalled();

        $channel = new Channel();
        $channel->setResource($resource->reveal());

        $channel->basicRecover(false);
    }

    public function testGetConnection()
    {
        $connection = $this->prophesize(Connection::class);
        $adapter = $this->prophesize(PhpAmqpLib::class);

        $channel = new Channel();
        $channel->setConnection($connection->reveal());

        static::assertSame($connection->reveal(), $channel->getConnection());
    }

    public function testCreateExchange()
    {
        $exchangeOptions = $this->prophesize(ExchangeOptions::class);
        $exchangePrototype = $this->prophesize(Exchange::class);

        $exchangePrototype->setChannel(Argument::type(Channel::class))->shouldBeCalled();
        $exchangePrototype->setOptions($exchangeOptions->reveal())->shouldBeCalled();

        $channel = new Channel($exchangePrototype->reveal());

        $exchange = $channel->createExchange($exchangeOptions->reveal());

        static::assertInstanceOf(Exchange::class, $exchange);
    }

    public function testCreateQueue()
    {
        $queueOptions = $this->prophesize(QueueOptions::class);
        $queuePrototype = $this->prophesize(Queue::class);

        $queuePrototype->setChannel(Argument::type(Channel::class))->shouldBeCalled();
        $queuePrototype->setOptions($queueOptions->reveal())->shouldBeCalled();

        $channel = new Channel(null, $queuePrototype->reveal());

        $exchange = $channel->createQueue($queueOptions->reveal());

        static::assertInstanceOf(Queue::class, $exchange);
    }
}
