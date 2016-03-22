<?php

namespace AMQPAL\Adapter\PhpAmqpLib;

use PhpAmqpLib\Message\AMQPMessage;
use AMQPAL\Adapter\ConsumerInterface;
use AMQPAL\Adapter\Message;

class ConsumerCallbackTest extends \PHPUnit_Framework_TestCase
{
    public function testGetDefaultMessageMapper()
    {
        $callable = function () {
            
        };
        $queue = $this->prophesize(Queue::class);

        $consumerCallback = new ConsumerCallback($callable, $queue->reveal());

        static::assertInstanceOf(MessageMapper::class, $consumerCallback->getMessageMapper());

        $messageMapper = $this->prophesize(MessageMapper::class);
        $consumerCallback->setMessageMapper($messageMapper->reveal());

        static::assertSame($messageMapper->reveal(), $consumerCallback->getMessageMapper());
    }

    public function testInvoke()
    {
        $queue = $this->prophesize(Queue::class);
        $callable = $this->prophesize(ConsumerInterface::class);
        $libMessage = $this->prophesize(AMQPMessage::class);
        $message = $this->prophesize(Message::class);
        $messageMapper = $this->prophesize(MessageMapper::class);

        $messageMapper->toMessage($libMessage->reveal())
            ->shouldBeCalled()
            ->willReturn($message);

        $callable->__invoke($message->reveal(), $queue->reveal())
            ->shouldBeCalled();

        $consumerCallback = new ConsumerCallback($callable->reveal(), $queue->reveal());
        $consumerCallback->setMessageMapper($messageMapper->reveal());

        $consumerCallback($libMessage->reveal());
    }
}
