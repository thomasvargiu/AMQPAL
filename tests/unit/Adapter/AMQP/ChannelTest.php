<?php

namespace AMQPAL\Adapter\AMQP;

use Prophecy\Argument;
use AMQPAL\Options\ExchangeOptions;
use AMQPAL\Options\QueueOptions;

class ChannelTest extends \PHPUnit_Framework_TestCase
{

    public function testSetResource()
    {
        $resource = $this->prophesize(\AMQPChannel::class);

        $channel = new Channel();
        $channel->setResource($resource->reveal());

        static::assertSame($resource->reveal(), $channel->getResource());
    }

    public function testIsConnected()
    {
        $resource = $this->prophesize(\AMQPChannel::class);

        $resource->isConnected()->shouldBeCalled()->willReturn(true);

        $channel = new Channel();
        $channel->setResource($resource->reveal());

        static::assertTrue($channel->isConnected());
    }

    public function testGetChannelId()
    {
        $resource = $this->prophesize(\AMQPChannel::class);

        $resource->getChannelId()->shouldBeCalled()->willReturn(1234);

        $channel = new Channel();
        $channel->setResource($resource->reveal());

        static::assertEquals(1234, $channel->getChannelId());
    }

    public function testSetQos()
    {
        $resource = $this->prophesize(\AMQPChannel::class);

        $resource->qos(2, 3)->shouldBeCalled();

        $channel = new Channel();
        $channel->setResource($resource->reveal());

        $channel->setQos(2, 3);
    }

    public function testStartTransaction()
    {
        $resource = $this->prophesize(\AMQPChannel::class);

        $resource->startTransaction()->shouldBeCalled();

        $channel = new Channel();
        $channel->setResource($resource->reveal());

        $channel->startTransaction();
    }

    public function testCommitTransaction()
    {
        $resource = $this->prophesize(\AMQPChannel::class);

        $resource->commitTransaction()->shouldBeCalled();

        $channel = new Channel();
        $channel->setResource($resource->reveal());

        $channel->commitTransaction();
    }

    public function testRollbackTransaction()
    {
        $resource = $this->prophesize(\AMQPChannel::class);

        $resource->rollbackTransaction()->shouldBeCalled();

        $channel = new Channel();
        $channel->setResource($resource->reveal());

        $channel->rollbackTransaction();
    }

    public function testBasicRecoverWithDefaults()
    {
        $resource = $this->prophesize(\AMQPChannel::class);

        $resource->basicRecover(true)->shouldBeCalled();

        $channel = new Channel();
        $channel->setResource($resource->reveal());

        $channel->basicRecover();
    }

    public function testBasicRecoverWithNoRequeue()
    {
        $resource = $this->prophesize(\AMQPChannel::class);

        $resource->basicRecover(false)->shouldBeCalled();

        $channel = new Channel();
        $channel->setResource($resource->reveal());

        $channel->basicRecover(false);
    }

    public function testGetConnection()
    {
        $connection = $this->prophesize(Connection::class);
        $adapter = $this->prophesize(AMQP::class);

        $adapter->getConnection()->willReturn($connection->reveal());

        $channel = new Channel();
        $channel->setConnection($connection->reveal());

        static::assertSame($connection->reveal(), $channel->getConnection());
    }

    public function testCreateExchangeWithResource()
    {
        $exchangeOptions = $this->prophesize(ExchangeOptions::class);
        $exchangePrototype = $this->prophesize(Exchange::class);
        $resource = $this->prophesize(\AMQPExchange::class);

        $exchangePrototype->setResource($resource->reveal())->shouldBeCalled();
        $exchangePrototype->setChannel(Argument::type(Channel::class))->shouldBeCalled();
        $exchangePrototype->setOptions($exchangeOptions->reveal())->shouldBeCalled();

        $channel = new Channel($exchangePrototype->reveal());

        $exchange = $channel->createExchange($exchangeOptions->reveal(), $resource->reveal());

        static::assertInstanceOf(Exchange::class, $exchange);
    }

    public function testCreateExchange()
    {
        $exchangeOptions = $this->prophesize(ExchangeOptions::class);
        $exchangePrototype = $this->prophesize(Exchange::class);
        $resource = $this->prophesize(\AMQPExchange::class);

        $exchangePrototype->setResource($resource->reveal())->shouldBeCalled();
        $exchangePrototype->setChannel(Argument::type(Channel::class))->shouldBeCalled();
        $exchangePrototype->setOptions($exchangeOptions->reveal())->shouldBeCalled();

        $channel = static::getMockBuilder(Channel::class)
            ->setMethods(['createExchangeResource'])
            ->setConstructorArgs([$exchangePrototype->reveal()])
            ->getMock();

        $channel->expects(static::once())
            ->method('createExchangeResource')
            ->willReturn($resource->reveal());

        $exchange = $channel->createExchange($exchangeOptions->reveal());

        static::assertInstanceOf(Exchange::class, $exchange);
    }

    public function testCreateQueueWithResource()
    {
        $queueOptions = $this->prophesize(QueueOptions::class);
        $queuePrototype = $this->prophesize(Queue::class);
        $resource = $this->prophesize(\AMQPQueue::class);

        $queuePrototype->setResource($resource->reveal())->shouldBeCalled();
        $queuePrototype->setChannel(Argument::type(Channel::class))->shouldBeCalled();
        $queuePrototype->setOptions($queueOptions->reveal())->shouldBeCalled();

        $channel = new Channel(null, $queuePrototype->reveal());

        $exchange = $channel->createQueue($queueOptions->reveal(), $resource->reveal());

        static::assertInstanceOf(Queue::class, $exchange);
    }

    public function testCreateQueue()
    {
        $queueOptions = $this->prophesize(QueueOptions::class);
        $queuePrototype = $this->prophesize(Queue::class);
        $resource = $this->prophesize(\AMQPQueue::class);

        $queuePrototype->setResource($resource->reveal())->shouldBeCalled();
        $queuePrototype->setChannel(Argument::type(Channel::class))->shouldBeCalled();
        $queuePrototype->setOptions($queueOptions->reveal())->shouldBeCalled();

        $channel = static::getMockBuilder(Channel::class)
            ->setMethods(['createQueueResource'])
            ->setConstructorArgs([null, $queuePrototype->reveal()])
            ->getMock();

        $channel->expects(static::once())
            ->method('createQueueResource')
            ->willReturn($resource->reveal());

        $exchange = $channel->createQueue($queueOptions->reveal());

        static::assertInstanceOf(Queue::class, $exchange);
    }
}
