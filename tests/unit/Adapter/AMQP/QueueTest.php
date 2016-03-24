<?php

namespace AMQPAL\Adapter\AMQP;

use AMQPAL\Adapter\Message;
use AMQPAL\Options;
use Prophecy\Argument;

class QueueTest extends \PHPUnit_Framework_TestCase
{

    public function testSetResource()
    {
        $resource = $this->prophesize(\AMQPQueue::class);

        $exchange = new Queue();
        $exchange->setResource($resource->reveal());

        static::assertSame($resource->reveal(), $exchange->getResource());
    }

    public function testGetDefaultMessageMapper()
    {
        $exchange = new Queue();

        static::assertInstanceOf(MessageMapper::class, $exchange->getMessageMapper());
    }

    public function testSetOptions()
    {
        $options = $this->getDefaultOptionsProphet();
        $resource = $this->prophesize(\AMQPQueue::class);

        $resource->setFlags(AMQP_NOPARAM | AMQP_DURABLE | AMQP_PASSIVE | AMQP_AUTODELETE | AMQP_EXCLUSIVE)
            ->shouldBeCalled();
        $resource->setName('queueName')->shouldBeCalled();
        $resource->setArguments(['arg1' => 'value1'])->shouldBeCalled();

        $queue = new Queue();
        $queue->setResource($resource->reveal());

        static::assertSame($queue, $queue->setOptions($options->reveal()));
        static::assertSame($options->reveal(), $queue->getOptions());
    }

    public function testDeclareQueue()
    {
        $options = $this->getDefaultOptionsProphet();
        $resource = $this->prophesize(\AMQPQueue::class);

        $resource->setFlags(AMQP_NOPARAM | AMQP_DURABLE | AMQP_PASSIVE | AMQP_AUTODELETE | AMQP_EXCLUSIVE)
            ->shouldBeCalled();
        $resource->setName('queueName')->shouldBeCalled();
        $resource->setArguments(['arg1' => 'value1'])->shouldBeCalled();

        $resource->declareQueue()->shouldBeCalled();

        $exchange = new Queue();
        $exchange->setResource($resource->reveal());
        $exchange->setOptions($options->reveal());

        static::assertSame($exchange, $exchange->declareQueue());
    }

    /**
     * @dataProvider deleteProvider()
     */
    public function testDelete($ifUnused, $ifEmpty, $noWait, $flags)
    {
        $options = $this->getDefaultOptionsProphet();
        $resource = $this->prophesize(\AMQPQueue::class);

        $resource->setFlags(AMQP_NOPARAM | AMQP_DURABLE | AMQP_PASSIVE | AMQP_AUTODELETE | AMQP_EXCLUSIVE)
            ->shouldBeCalled();
        $resource->setName('queueName')->shouldBeCalled();
        $resource->setArguments(['arg1' => 'value1'])->shouldBeCalled();

        $resource->delete($flags)->shouldBeCalled();

        $exchange = new Queue();
        $exchange->setResource($resource->reveal());
        $exchange->setOptions($options->reveal());

        static::assertSame($exchange, $exchange->delete($ifUnused, $ifEmpty, $noWait));
    }

    public function testBind()
    {
        $arguments = ['arg2' => 'value2'];

        $options = $this->getDefaultOptionsProphet();
        $resource = $this->prophesize(\AMQPQueue::class);

        $resource->setFlags(AMQP_NOPARAM | AMQP_DURABLE | AMQP_PASSIVE | AMQP_AUTODELETE | AMQP_EXCLUSIVE)
            ->shouldBeCalled();
        $resource->setName('queueName')->shouldBeCalled();
        $resource->setArguments(['arg1' => 'value1'])->shouldBeCalled();

        $resource->bind('exchangeName', 'routingKey', $arguments)->shouldBeCalled();

        $queue = new Queue();
        $queue->setResource($resource->reveal());
        $queue->setOptions($options->reveal());

        static::assertSame($queue, $queue->bind('exchangeName', 'routingKey', false, $arguments));
    }

    public function testUnbind()
    {
        $arguments = ['arg2' => 'value2'];

        $options = $this->getDefaultOptionsProphet();
        $resource = $this->prophesize(\AMQPQueue::class);

        $resource->setFlags(AMQP_NOPARAM | AMQP_DURABLE | AMQP_PASSIVE | AMQP_AUTODELETE | AMQP_EXCLUSIVE)
            ->shouldBeCalled();
        $resource->setName('queueName')->shouldBeCalled();
        $resource->setArguments(['arg1' => 'value1'])->shouldBeCalled();

        $resource->unbind('exchangeName', 'routingKey', $arguments)->shouldBeCalled();

        $queue = new Queue();
        $queue->setResource($resource->reveal());
        $queue->setOptions($options->reveal());

        static::assertSame($queue, $queue->unbind('exchangeName', 'routingKey', $arguments));
    }

    public function testAck()
    {
        $options = $this->getDefaultOptionsProphet();
        $resource = $this->prophesize(\AMQPQueue::class);

        $resource->setFlags(AMQP_NOPARAM | AMQP_DURABLE | AMQP_PASSIVE | AMQP_AUTODELETE | AMQP_EXCLUSIVE)
            ->shouldBeCalled();
        $resource->setName('queueName')->shouldBeCalled();
        $resource->setArguments(['arg1' => 'value1'])->shouldBeCalled();

        $resource->ack('deliveryTag', AMQP_NOPARAM)->shouldBeCalled();

        $queue = new Queue();
        $queue->setResource($resource->reveal());
        $queue->setOptions($options->reveal());

        static::assertSame($queue, $queue->ack('deliveryTag'));
    }

    public function testAckMultiple()
    {
        $options = $this->getDefaultOptionsProphet();
        $resource = $this->prophesize(\AMQPQueue::class);

        $resource->setFlags(AMQP_NOPARAM | AMQP_DURABLE | AMQP_PASSIVE | AMQP_AUTODELETE | AMQP_EXCLUSIVE)
            ->shouldBeCalled();
        $resource->setName('queueName')->shouldBeCalled();
        $resource->setArguments(['arg1' => 'value1'])->shouldBeCalled();

        $resource->ack('deliveryTag', AMQP_MULTIPLE)->shouldBeCalled();

        $queue = new Queue();
        $queue->setResource($resource->reveal());
        $queue->setOptions($options->reveal());

        static::assertSame($queue, $queue->ack('deliveryTag', true));
    }

    public function testNack()
    {
        $options = $this->getDefaultOptionsProphet();
        $resource = $this->prophesize(\AMQPQueue::class);

        $resource->setFlags(AMQP_NOPARAM | AMQP_DURABLE | AMQP_PASSIVE | AMQP_AUTODELETE | AMQP_EXCLUSIVE)
            ->shouldBeCalled();
        $resource->setName('queueName')->shouldBeCalled();
        $resource->setArguments(['arg1' => 'value1'])->shouldBeCalled();

        $resource->nack('deliveryTag', AMQP_NOPARAM)->shouldBeCalled();

        $queue = new Queue();
        $queue->setResource($resource->reveal());
        $queue->setOptions($options->reveal());

        static::assertSame($queue, $queue->nack('deliveryTag'));
    }

    public function testNackRequeue()
    {
        $options = $this->getDefaultOptionsProphet();
        $resource = $this->prophesize(\AMQPQueue::class);

        $resource->setFlags(AMQP_NOPARAM | AMQP_DURABLE | AMQP_PASSIVE | AMQP_AUTODELETE | AMQP_EXCLUSIVE)
            ->shouldBeCalled();
        $resource->setName('queueName')->shouldBeCalled();
        $resource->setArguments(['arg1' => 'value1'])->shouldBeCalled();

        $resource->nack('deliveryTag', AMQP_REQUEUE)->shouldBeCalled();

        $queue = new Queue();
        $queue->setResource($resource->reveal());
        $queue->setOptions($options->reveal());

        static::assertSame($queue, $queue->nack('deliveryTag', true));
    }

    public function testNackMultiple()
    {
        $options = $this->getDefaultOptionsProphet();
        $resource = $this->prophesize(\AMQPQueue::class);

        $resource->setFlags(AMQP_NOPARAM | AMQP_DURABLE | AMQP_PASSIVE | AMQP_AUTODELETE | AMQP_EXCLUSIVE)
            ->shouldBeCalled();
        $resource->setName('queueName')->shouldBeCalled();
        $resource->setArguments(['arg1' => 'value1'])->shouldBeCalled();

        $resource->nack('deliveryTag', AMQP_MULTIPLE)->shouldBeCalled();

        $queue = new Queue();
        $queue->setResource($resource->reveal());
        $queue->setOptions($options->reveal());

        static::assertSame($queue, $queue->nack('deliveryTag', false, true));
    }

    public function testNackRequeueMultiple()
    {
        $options = $this->getDefaultOptionsProphet();
        $resource = $this->prophesize(\AMQPQueue::class);

        $resource->setFlags(AMQP_NOPARAM | AMQP_DURABLE | AMQP_PASSIVE | AMQP_AUTODELETE | AMQP_EXCLUSIVE)
            ->shouldBeCalled();
        $resource->setName('queueName')->shouldBeCalled();
        $resource->setArguments(['arg1' => 'value1'])->shouldBeCalled();

        $resource->nack('deliveryTag', AMQP_REQUEUE | AMQP_MULTIPLE)->shouldBeCalled();

        $queue = new Queue();
        $queue->setResource($resource->reveal());
        $queue->setOptions($options->reveal());

        static::assertSame($queue, $queue->nack('deliveryTag', true, true));
    }

    public function testReject()
    {
        $options = $this->getDefaultOptionsProphet();
        $resource = $this->prophesize(\AMQPQueue::class);

        $resource->setFlags(AMQP_NOPARAM | AMQP_DURABLE | AMQP_PASSIVE | AMQP_AUTODELETE | AMQP_EXCLUSIVE)
            ->shouldBeCalled();
        $resource->setName('queueName')->shouldBeCalled();
        $resource->setArguments(['arg1' => 'value1'])->shouldBeCalled();

        $resource->reject('deliveryTag', AMQP_NOPARAM)->shouldBeCalled();

        $queue = new Queue();
        $queue->setResource($resource->reveal());
        $queue->setOptions($options->reveal());

        static::assertSame($queue, $queue->reject('deliveryTag'));
    }

    public function testRejectRequeue()
    {
        $options = $this->getDefaultOptionsProphet();
        $resource = $this->prophesize(\AMQPQueue::class);

        $resource->setFlags(AMQP_NOPARAM | AMQP_DURABLE | AMQP_PASSIVE | AMQP_AUTODELETE | AMQP_EXCLUSIVE)
            ->shouldBeCalled();
        $resource->setName('queueName')->shouldBeCalled();
        $resource->setArguments(['arg1' => 'value1'])->shouldBeCalled();

        $resource->reject('deliveryTag', AMQP_REQUEUE)->shouldBeCalled();

        $queue = new Queue();
        $queue->setResource($resource->reveal());
        $queue->setOptions($options->reveal());

        static::assertSame($queue, $queue->reject('deliveryTag', true));
    }

    public function testPurge()
    {
        $options = $this->getDefaultOptionsProphet();
        $resource = $this->prophesize(\AMQPQueue::class);

        $resource->setFlags(AMQP_NOPARAM | AMQP_DURABLE | AMQP_PASSIVE | AMQP_AUTODELETE | AMQP_EXCLUSIVE)
            ->shouldBeCalled();
        $resource->setName('queueName')->shouldBeCalled();
        $resource->setArguments(['arg1' => 'value1'])->shouldBeCalled();

        $resource->purge()->shouldBeCalled();

        $queue = new Queue();
        $queue->setResource($resource->reveal());
        $queue->setOptions($options->reveal());

        static::assertSame($queue, $queue->purge());
    }

    public function testCancel()
    {
        $options = $this->getDefaultOptionsProphet();
        $resource = $this->prophesize(\AMQPQueue::class);

        $resource->setFlags(AMQP_NOPARAM | AMQP_DURABLE | AMQP_PASSIVE | AMQP_AUTODELETE | AMQP_EXCLUSIVE)
            ->shouldBeCalled();
        $resource->setName('queueName')->shouldBeCalled();
        $resource->setArguments(['arg1' => 'value1'])->shouldBeCalled();

        $resource->cancel('')->shouldBeCalled();

        $queue = new Queue();
        $queue->setResource($resource->reveal());
        $queue->setOptions($options->reveal());

        static::assertSame($queue, $queue->cancel(null));
    }

    public function testGet()
    {
        $options = $this->getDefaultOptionsProphet();
        $resource = $this->prophesize(\AMQPQueue::class);
        $messageMapper = $this->prophesize(MessageMapper::class);

        $resource->setFlags(AMQP_NOPARAM | AMQP_DURABLE | AMQP_PASSIVE | AMQP_AUTODELETE | AMQP_EXCLUSIVE)
            ->shouldBeCalled();
        $resource->setName('queueName')->shouldBeCalled();
        $resource->setArguments(['arg1' => 'value1'])->shouldBeCalled();

        $resource->get(AMQP_NOPARAM)->shouldBeCalled();

        $queue = new Queue();
        $queue->setResource($resource->reveal());
        $queue->setOptions($options->reveal());
        $queue->setMessageMapper($messageMapper->reveal());

        static::assertNull($queue->get());
    }

    public function testGetWithAutoAck()
    {
        $options = $this->getDefaultOptionsProphet();
        $resource = $this->prophesize(\AMQPQueue::class);
        $messageMapper = $this->prophesize(MessageMapper::class);

        $messageMapper->toMessage(Argument::any())->shouldNotBeCalled();

        $resource->setFlags(AMQP_NOPARAM | AMQP_DURABLE | AMQP_PASSIVE | AMQP_AUTODELETE | AMQP_EXCLUSIVE)
            ->shouldBeCalled();
        $resource->setName('queueName')->shouldBeCalled();
        $resource->setArguments(['arg1' => 'value1'])->shouldBeCalled();

        $resource->get(AMQP_AUTOACK)->shouldBeCalled();

        $queue = new Queue();
        $queue->setResource($resource->reveal());
        $queue->setOptions($options->reveal());
        $queue->setMessageMapper($messageMapper->reveal());

        static::assertNull($queue->get(true));
    }

    public function testGetWithAutoAckAndMessage()
    {
        $options = $this->getDefaultOptionsProphet();
        $resource = $this->prophesize(\AMQPQueue::class);
        $message = $this->prophesize(Message::class);
        $libMessage = $this->prophesize(\AMQPEnvelope::class);
        $messageMapper = $this->prophesize(MessageMapper::class);

        $messageMapper->toMessage($libMessage->reveal())->shouldBeCalled()->willReturn($message->reveal());

        $resource->setFlags(AMQP_NOPARAM | AMQP_DURABLE | AMQP_PASSIVE | AMQP_AUTODELETE | AMQP_EXCLUSIVE)
            ->shouldBeCalled();
        $resource->setName('queueName')->shouldBeCalled();
        $resource->setArguments(['arg1' => 'value1'])->shouldBeCalled();

        $resource->get(AMQP_AUTOACK)
            ->shouldBeCalled()
            ->willReturn($libMessage->reveal());

        $queue = new Queue();
        $queue->setResource($resource->reveal());
        $queue->setOptions($options->reveal());
        $queue->setMessageMapper($messageMapper->reveal());

        static::assertSame($message->reveal(), $queue->get(true));
    }

    public function testConsume()
    {
        $options = $this->getDefaultOptionsProphet();
        $resource = $this->prophesize(\AMQPQueue::class);
        $libMessage = $this->prophesize(\AMQPEnvelope::class);
        $messageMapper = $this->prophesize(MessageMapper::class);

        $resource->setFlags(AMQP_NOPARAM | AMQP_DURABLE | AMQP_PASSIVE | AMQP_AUTODELETE | AMQP_EXCLUSIVE)
            ->shouldBeCalled();
        $resource->setName('queueName')->shouldBeCalled();
        $resource->setArguments(['arg1' => 'value1'])->shouldBeCalled();

        $resource->consume(Argument::type(ConsumerCallback::class), AMQP_NOPARAM, 'consumerTag')
            ->shouldBeCalled()
            ->willReturn($libMessage->reveal());

        $queue = new Queue();
        $queue->setResource($resource->reveal());
        $queue->setOptions($options->reveal());
        $queue->setMessageMapper($messageMapper->reveal());

        $callback = function () {
            
        };

        $ret = $queue->consume('consumerTag', false, false, false, $callback);

        static::assertSame($queue, $ret);
    }

    /**
     * @dataProvider consumeProviderWithFlags
     */
    public function testConsumeWithFlags(array $consumeArgs, $flags, $consumerTag)
    {
        $options = $this->getDefaultOptionsProphet();
        $resource = $this->prophesize(\AMQPQueue::class);
        $libMessage = $this->prophesize(\AMQPEnvelope::class);
        $messageMapper = $this->prophesize(MessageMapper::class);

        $resource->setFlags(AMQP_NOPARAM | AMQP_DURABLE | AMQP_PASSIVE | AMQP_AUTODELETE | AMQP_EXCLUSIVE)
            ->shouldBeCalled();
        $resource->setName('queueName')->shouldBeCalled();
        $resource->setArguments(['arg1' => 'value1'])->shouldBeCalled();

        $resource->consume(null, $flags, $consumerTag)
            ->shouldBeCalled()
            ->willReturn($libMessage->reveal());

        $queue = new Queue();
        $queue->setResource($resource->reveal());
        $queue->setOptions($options->reveal());
        $queue->setMessageMapper($messageMapper->reveal());

        $ret = call_user_func_array([$queue, 'consume'], $consumeArgs);
        static::assertSame($queue, $ret);
    }

    public function consumeProviderWithFlags()
    {
        return [
            [
                [null, false, false, false, null],
                AMQP_NOPARAM, null
            ],
            [
                [null, true, false, false, null],
                AMQP_NOLOCAL, null
            ],
            [
                [null, false, true, false, null],
                AMQP_AUTOACK, null
            ],
            [
                [null, false, false, true, null],
                AMQP_EXCLUSIVE, null
            ],
            [
                [null, true, true, true, null],
                AMQP_NOLOCAL | AMQP_AUTOACK | AMQP_EXCLUSIVE, null
            ]
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
            [true, true, true, AMQP_NOPARAM | AMQP_IFUNUSED | AMQP_IFEMPTY | AMQP_NOWAIT],
            [true, true, false, AMQP_NOPARAM | AMQP_IFUNUSED | AMQP_IFEMPTY],
            [true, false, true, AMQP_NOPARAM | AMQP_IFUNUSED | AMQP_NOWAIT],
            [true, false, false, AMQP_NOPARAM | AMQP_IFUNUSED],
            [false, true, true, AMQP_NOPARAM | AMQP_IFEMPTY | AMQP_NOWAIT],
            [false, true, false, AMQP_NOPARAM | AMQP_IFEMPTY],
            [false, false, true, AMQP_NOPARAM | AMQP_NOWAIT],
            [false, false, false, AMQP_NOPARAM],
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
