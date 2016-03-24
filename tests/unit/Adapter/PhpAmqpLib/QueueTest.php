<?php

namespace AMQPAL\Adapter\PhpAmqpLib;

use PhpAmqpLib\Channel\AMQPChannel;
use PhpAmqpLib\Message\AMQPMessage;
use AMQPAL\Adapter\Message;
use AMQPAL\Options;
use Prophecy\Argument;

class QueueTest extends \PHPUnit_Framework_TestCase
{

    public function testSetOptions()
    {
        $options = $this->getDefaultOptionsProphet();

        $queue = new Queue();

        static::assertSame($queue, $queue->setOptions($options->reveal()));
        static::assertSame($options->reveal(), $queue->getOptions());
    }

    public function testSetOptionsWithArray()
    {
        $options = ['name' => 'queueName'];

        $queue = new Queue();

        static::assertSame($queue, $queue->setOptions($options));
        $queueOptions = $queue->getOptions();
        static::assertInstanceOf(Options\QueueOptions::class, $queueOptions);
        static::assertEquals('queueName', $queueOptions->getName());
    }

    public function testGetDefaultMessageMapper()
    {
        $exchange = new Queue();

        static::assertInstanceOf(MessageMapper::class, $exchange->getMessageMapper());
    }

    public function testDeclareQueue()
    {
        /** @var \Prophecy\Prophecy\ObjectProphecy|AMQPChannel $adapterChannel */
        $adapterChannel = $this->prophesize(AMQPChannel::class);
        /** @var \Prophecy\Prophecy\ObjectProphecy|Channel $channel */
        $channel = $this->prophesize(Channel::class);
        $options = $this->getDefaultOptionsProphet();

        $options->isDurable()->willReturn(true);
        $options->isPassive()->willReturn(true);
        $options->isAutoDelete()->willReturn(true);
        $options->isExclusive()->willReturn(true);
        $options->getName()->willReturn('queueName');
        $options->getArguments()->willReturn(['arg1' => 'value1']);
        $options->isExclusive()->willReturn(true);

        $channel->getResource()->willReturn($adapterChannel);
        $adapterChannel->queue_declare(
            'queueName',
            true,
            true,
            true,
            true,
            false,
            ['arg1' => 'value1']
        )->shouldBeCalled();

        $queue = new Queue();
        $queue->setChannel($channel->reveal());
        $queue->setOptions($options->reveal());

        static::assertSame($queue, $queue->declareQueue());
    }

    /**
     * @dataProvider deleteProvider()
     */
    public function testDelete($ifUnused, $ifEmpty, $noWait)
    {
        /** @var \Prophecy\Prophecy\ObjectProphecy|AMQPChannel $adapterChannel */
        $adapterChannel = $this->prophesize(AMQPChannel::class);
        /** @var \Prophecy\Prophecy\ObjectProphecy|Channel $channel */
        $channel = $this->prophesize(Channel::class);
        $options = $this->getDefaultOptionsProphet();

        $adapterChannel->queue_delete('queueName', $ifUnused, $ifEmpty, $noWait)->shouldBeCalled();
        $channel->getResource()->willReturn($adapterChannel->reveal());

        $queue = new Queue();
        $queue->setOptions($options->reveal());
        $queue->setChannel($channel->reveal());

        static::assertSame($queue, $queue->delete($ifUnused, $ifEmpty, $noWait));
    }

    public function testBind()
    {
        $arguments = ['arg2' => 'value2'];

        /** @var \Prophecy\Prophecy\ObjectProphecy|AMQPChannel $adapterChannel */
        $adapterChannel = $this->prophesize(AMQPChannel::class);
        /** @var \Prophecy\Prophecy\ObjectProphecy|Channel $channel */
        $channel = $this->prophesize(Channel::class);
        $options = $this->getDefaultOptionsProphet();

        $adapterChannel->queue_bind('queueName', 'exchangeName', 'routingKey', false, $arguments)->shouldBeCalled();
        $channel->getResource()->willReturn($adapterChannel->reveal());

        $queue = new Queue();
        $queue->setOptions($options->reveal());
        $queue->setChannel($channel->reveal());

        static::assertSame($queue, $queue->bind('exchangeName', 'routingKey', false, $arguments));
    }

    public function testBindNoWaitAndNullRoutingKey()
    {
        $arguments = ['arg2' => 'value2'];

        /** @var \Prophecy\Prophecy\ObjectProphecy|AMQPChannel $adapterChannel */
        $adapterChannel = $this->prophesize(AMQPChannel::class);
        /** @var \Prophecy\Prophecy\ObjectProphecy|Channel $channel */
        $channel = $this->prophesize(Channel::class);
        $options = $this->getDefaultOptionsProphet();

        $adapterChannel->queue_bind('queueName', 'exchangeName', '', true, $arguments)->shouldBeCalled();
        $channel->getResource()->willReturn($adapterChannel->reveal());

        $queue = new Queue();
        $queue->setOptions($options->reveal());
        $queue->setChannel($channel->reveal());

        static::assertSame($queue, $queue->bind('exchangeName', null, true, $arguments));
    }

    public function testUnbind()
    {
        $arguments = ['arg2' => 'value2'];

        /** @var \Prophecy\Prophecy\ObjectProphecy|AMQPChannel $adapterChannel */
        $adapterChannel = $this->prophesize(AMQPChannel::class);
        /** @var \Prophecy\Prophecy\ObjectProphecy|Channel $channel */
        $channel = $this->prophesize(Channel::class);
        $options = $this->getDefaultOptionsProphet();

        $adapterChannel->queue_unbind('queueName', 'exchangeName', 'routingKey', $arguments)->shouldBeCalled();
        $channel->getResource()->willReturn($adapterChannel->reveal());

        $queue = new Queue();
        $queue->setOptions($options->reveal());
        $queue->setChannel($channel->reveal());

        static::assertSame($queue, $queue->unbind('exchangeName', 'routingKey', $arguments));
    }

    public function testUnbindWithNullRoutingKey()
    {
        $arguments = ['arg2' => 'value2'];

        /** @var \Prophecy\Prophecy\ObjectProphecy|AMQPChannel $adapterChannel */
        $adapterChannel = $this->prophesize(AMQPChannel::class);
        /** @var \Prophecy\Prophecy\ObjectProphecy|Channel $channel */
        $channel = $this->prophesize(Channel::class);
        $options = $this->getDefaultOptionsProphet();

        $adapterChannel->queue_unbind('queueName', 'exchangeName', '', $arguments)->shouldBeCalled();
        $channel->getResource()->willReturn($adapterChannel->reveal());

        $queue = new Queue();
        $queue->setOptions($options->reveal());
        $queue->setChannel($channel->reveal());

        static::assertSame($queue, $queue->unbind('exchangeName', null, $arguments));
    }

    public function testAck()
    {
        /** @var \Prophecy\Prophecy\ObjectProphecy|AMQPChannel $adapterChannel */
        $adapterChannel = $this->prophesize(AMQPChannel::class);
        /** @var \Prophecy\Prophecy\ObjectProphecy|Channel $channel */
        $channel = $this->prophesize(Channel::class);
        $options = $this->getDefaultOptionsProphet();

        $adapterChannel->basic_ack('deliveryTag', false)->shouldBeCalled();
        $channel->getResource()->willReturn($adapterChannel->reveal());

        $queue = new Queue();
        $queue->setOptions($options->reveal());
        $queue->setChannel($channel->reveal());

        static::assertSame($queue, $queue->ack('deliveryTag'));
    }

    public function testAckMultiple()
    {
        /** @var \Prophecy\Prophecy\ObjectProphecy|AMQPChannel $adapterChannel */
        $adapterChannel = $this->prophesize(AMQPChannel::class);
        /** @var \Prophecy\Prophecy\ObjectProphecy|Channel $channel */
        $channel = $this->prophesize(Channel::class);
        $options = $this->getDefaultOptionsProphet();

        $adapterChannel->basic_ack('deliveryTag', true)->shouldBeCalled();
        $channel->getResource()->willReturn($adapterChannel->reveal());

        $queue = new Queue();
        $queue->setOptions($options->reveal());
        $queue->setChannel($channel->reveal());

        static::assertSame($queue, $queue->ack('deliveryTag', true));
    }

    public function testNack()
    {
        /** @var \Prophecy\Prophecy\ObjectProphecy|AMQPChannel $adapterChannel */
        $adapterChannel = $this->prophesize(AMQPChannel::class);
        /** @var \Prophecy\Prophecy\ObjectProphecy|Channel $channel */
        $channel = $this->prophesize(Channel::class);
        $options = $this->getDefaultOptionsProphet();

        $adapterChannel->basic_nack('deliveryTag', false, false)->shouldBeCalled();
        $channel->getResource()->willReturn($adapterChannel->reveal());

        $queue = new Queue();
        $queue->setOptions($options->reveal());
        $queue->setChannel($channel->reveal());

        static::assertSame($queue, $queue->nack('deliveryTag'));
    }

    public function testNackRequeue()
    {
        /** @var \Prophecy\Prophecy\ObjectProphecy|AMQPChannel $adapterChannel */
        $adapterChannel = $this->prophesize(AMQPChannel::class);
        /** @var \Prophecy\Prophecy\ObjectProphecy|Channel $channel */
        $channel = $this->prophesize(Channel::class);
        $options = $this->getDefaultOptionsProphet();

        $adapterChannel->basic_nack('deliveryTag', false, true)->shouldBeCalled();
        $channel->getResource()->willReturn($adapterChannel->reveal());

        $queue = new Queue();
        $queue->setOptions($options->reveal());
        $queue->setChannel($channel->reveal());

        static::assertSame($queue, $queue->nack('deliveryTag', true));
    }

    public function testNackMultiple()
    {
        /** @var \Prophecy\Prophecy\ObjectProphecy|AMQPChannel $adapterChannel */
        $adapterChannel = $this->prophesize(AMQPChannel::class);
        /** @var \Prophecy\Prophecy\ObjectProphecy|Channel $channel */
        $channel = $this->prophesize(Channel::class);
        $options = $this->getDefaultOptionsProphet();

        $adapterChannel->basic_nack('deliveryTag', true, false)->shouldBeCalled();
        $channel->getResource()->willReturn($adapterChannel->reveal());

        $queue = new Queue();
        $queue->setOptions($options->reveal());
        $queue->setChannel($channel->reveal());

        static::assertSame($queue, $queue->nack('deliveryTag', false, true));
    }

    public function testNackRequeueMultiple()
    {
        /** @var \Prophecy\Prophecy\ObjectProphecy|AMQPChannel $adapterChannel */
        $adapterChannel = $this->prophesize(AMQPChannel::class);
        /** @var \Prophecy\Prophecy\ObjectProphecy|Channel $channel */
        $channel = $this->prophesize(Channel::class);
        $options = $this->getDefaultOptionsProphet();

        $adapterChannel->basic_nack('deliveryTag', true, true)->shouldBeCalled();
        $channel->getResource()->willReturn($adapterChannel->reveal());

        $queue = new Queue();
        $queue->setOptions($options->reveal());
        $queue->setChannel($channel->reveal());

        static::assertSame($queue, $queue->nack('deliveryTag', true, true));
    }

    public function testReject()
    {
        /** @var \Prophecy\Prophecy\ObjectProphecy|AMQPChannel $adapterChannel */
        $adapterChannel = $this->prophesize(AMQPChannel::class);
        /** @var \Prophecy\Prophecy\ObjectProphecy|Channel $channel */
        $channel = $this->prophesize(Channel::class);
        $options = $this->getDefaultOptionsProphet();

        $adapterChannel->basic_reject('deliveryTag', false)->shouldBeCalled();
        $channel->getResource()->willReturn($adapterChannel->reveal());

        $queue = new Queue();
        $queue->setOptions($options->reveal());
        $queue->setChannel($channel->reveal());

        static::assertSame($queue, $queue->reject('deliveryTag'));
    }

    public function testRejectRequeue()
    {
        /** @var \Prophecy\Prophecy\ObjectProphecy|AMQPChannel $adapterChannel */
        $adapterChannel = $this->prophesize(AMQPChannel::class);
        /** @var \Prophecy\Prophecy\ObjectProphecy|Channel $channel */
        $channel = $this->prophesize(Channel::class);
        $options = $this->getDefaultOptionsProphet();

        $adapterChannel->basic_reject('deliveryTag', true)->shouldBeCalled();
        $channel->getResource()->willReturn($adapterChannel->reveal());

        $queue = new Queue();
        $queue->setOptions($options->reveal());
        $queue->setChannel($channel->reveal());

        static::assertSame($queue, $queue->reject('deliveryTag', true));
    }

    public function testPurge()
    {
        /** @var \Prophecy\Prophecy\ObjectProphecy|AMQPChannel $adapterChannel */
        $adapterChannel = $this->prophesize(AMQPChannel::class);
        /** @var \Prophecy\Prophecy\ObjectProphecy|Channel $channel */
        $channel = $this->prophesize(Channel::class);
        $options = $this->getDefaultOptionsProphet();

        $adapterChannel->queue_purge('queueName')->shouldBeCalled();
        $channel->getResource()->willReturn($adapterChannel->reveal());

        $queue = new Queue();
        $queue->setOptions($options->reveal());
        $queue->setChannel($channel->reveal());

        static::assertSame($queue, $queue->purge());
    }

    public function testCancel()
    {
        /** @var \Prophecy\Prophecy\ObjectProphecy|AMQPChannel $adapterChannel */
        $adapterChannel = $this->prophesize(AMQPChannel::class);
        /** @var \Prophecy\Prophecy\ObjectProphecy|Channel $channel */
        $channel = $this->prophesize(Channel::class);
        $options = $this->getDefaultOptionsProphet();

        $adapterChannel->basic_cancel('consumerTag')->shouldBeCalled();
        $channel->getResource()->willReturn($adapterChannel->reveal());

        $queue = new Queue();
        $queue->setOptions($options->reveal());
        $queue->setChannel($channel->reveal());

        static::assertSame($queue, $queue->cancel('consumerTag'));
    }

    public function testGetWithAutoAck()
    {
        /** @var \Prophecy\Prophecy\ObjectProphecy|AMQPChannel $adapterChannel */
        $adapterChannel = $this->prophesize(AMQPChannel::class);
        /** @var \Prophecy\Prophecy\ObjectProphecy|Channel $channel */
        $channel = $this->prophesize(Channel::class);
        $options = $this->getDefaultOptionsProphet();
        $messageMapper = $this->prophesize(MessageMapper::class);

        $messageMapper->toMessage(Argument::any())->shouldNotBeCalled();

        $adapterChannel->basic_get('queueName', true)
            ->shouldBeCalled()
            ->willReturn(null);
        $channel->getResource()->willReturn($adapterChannel->reveal());

        $queue = new Queue();
        $queue->setOptions($options->reveal());
        $queue->setChannel($channel->reveal());
        $queue->setMessageMapper($messageMapper->reveal());

        static::assertNull($queue->get(true));
    }

    public function testGetWithAutoAckAndMessage()
    {
        /** @var \Prophecy\Prophecy\ObjectProphecy|AMQPChannel $adapterChannel */
        $adapterChannel = $this->prophesize(AMQPChannel::class);
        /** @var \Prophecy\Prophecy\ObjectProphecy|Channel $channel */
        $channel = $this->prophesize(Channel::class);
        $options = $this->getDefaultOptionsProphet();
        $message = $this->prophesize(Message::class);
        $libMessage = $this->prophesize(AMQPMessage::class);
        $messageMapper = $this->prophesize(MessageMapper::class);

        $messageMapper->toMessage($libMessage->reveal())->shouldBeCalled()->willReturn($message->reveal());

        $adapterChannel->basic_get('queueName', true)
            ->shouldBeCalled()
            ->willReturn($libMessage->reveal());
        $channel->getResource()->willReturn($adapterChannel->reveal());

        $queue = new Queue();
        $queue->setOptions($options->reveal());
        $queue->setChannel($channel->reveal());
        $queue->setMessageMapper($messageMapper->reveal());

        static::assertSame($message->reveal(), $queue->get(true));
    }

    /**
     * @dataProvider consumeProvider
     */
    public function testConsume($args, $libArgs)
    {
        $adapterChannel = $this->prophesize(AMQPChannel::class);
        $channel = $this->prophesize(Channel::class);
        $options = $this->getDefaultOptionsProphet();
        $libMessage = $this->prophesize(AMQPMessage::class);
        $messageMapper = $this->prophesize(MessageMapper::class);

        $adapterChannel->basic_consume(
            'queueName',
            $libArgs[3],
            $libArgs[0],
            $libArgs[1],
            $libArgs[2],
            false,
            Argument::type(ConsumerCallback::class)
        )
            ->shouldBeCalled()
            ->willReturn($libMessage->reveal());

        $adapterChannel->callbacks = ['foo', 'bar'];
        $adapterChannel->wait()->shouldBeCalledTimes(count($adapterChannel->callbacks))
            ->will(function () use ($adapterChannel) {
                $callbacks = $adapterChannel->callbacks;
                array_shift($callbacks);
                $adapterChannel->callbacks = $callbacks;
            });

        $channel->getResource()->willReturn($adapterChannel->reveal());

        $queue = new Queue();
        $queue->setOptions($options->reveal());
        $queue->setChannel($channel->reveal());
        $queue->setMessageMapper($messageMapper->reveal());

        $callback = function () {
            
        };

        $ret = $queue->consume($callback, $args[0], $args[1], $args[2], $args[3]);

        static::assertSame($queue, $ret);
    }

    public function consumeProvider()
    {
        return [
            [
                [true, true, true, null],
                [true, true, true, ''],
            ],
            [
                [true, true, true, 'consumerTag'],
                [true, true, true, 'consumerTag'],
            ],
            [
                [false, false, false, 'consumerTag'],
                [false, false, false, 'consumerTag'],
            ],
        ];
    }

    public function testSetAndGetChannel()
    {
        $channel = $this->prophesize(Channel::class);

        $exchange = new Queue();
        static::assertSame($exchange, $exchange->setChannel($channel->reveal()));
        static::assertSame($channel->reveal(), $exchange->getChannel());
    }

    public function testGetConnection()
    {
        $channel = $this->prophesize(Channel::class);
        $connection = $this->prophesize(Connection::class);

        $channel->getConnection()->willReturn($connection);

        $exchange = new Queue();
        static::assertSame($exchange, $exchange->setChannel($channel->reveal()));
        static::assertSame($connection->reveal(), $exchange->getConnection());
    }

    public function deleteProvider()
    {
        return [
            [true, true, true],
            [true, true, false],
            [true, false, true],
            [true, false, false],
            [false, true, true],
            [false, true, false],
            [false, false, true],
            [false, false, false],
        ];
    }

    /**
     * @return Options\QueueOptions|\Prophecy\Prophecy\ObjectProphecy
     */
    protected function getDefaultOptionsProphet()
    {
        /** @var Options\QueueOptions|\Prophecy\Prophecy\ObjectProphecy $options */
        $options = $this->prophesize(Options\QueueOptions::class);

        $options->isDurable()->willReturn(true);
        $options->isPassive()->willReturn(true);
        $options->isAutoDelete()->willReturn(true);
        $options->isExclusive()->willReturn(true);
        $options->getName()->willReturn('queueName');
        $options->getArguments()->willReturn(['arg1' => 'value1']);
        $options->isExclusive()->willReturn(true);

        return $options;
    }
}
