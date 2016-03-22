<?php

namespace AMQPAL\Adapter\AMQP;

use AMQPAL\Adapter\Message;

class MessageMapperTest extends \PHPUnit_Framework_TestCase
{
    public function testGetMessagePrototype()
    {
        $mapper = new MessageMapper();

        static::assertInstanceOf(Message::class, $mapper->getMessagePrototype());

        $message = $this->prophesize(Message::class);
        $mapper->setMessagePrototype($message->reveal());

        static::assertSame($message->reveal(), $mapper->getMessagePrototype());
    }

    public function testToMessage()
    {
        $libMessage = $this->prophesize(\AMQPEnvelope::class);
        $message = $this->prophesize(Message::class);

        $mapper = new MessageMapper();
        $mapper->setMessagePrototype($message->reveal());

        $libMessage->getBody()->shouldBeCalled()->willReturn('body');
        $libMessage->getRoutingKey()->shouldBeCalled()->willReturn('routingKey');
        $libMessage->getDeliveryTag()->shouldBeCalled()->willReturn('deliveryTag');
        $libMessage->getDeliveryMode()->shouldBeCalled()->willReturn(2);
        $libMessage->getExchangeName()->shouldBeCalled()->willReturn('exchangeName');
        $libMessage->isRedelivery()->shouldBeCalled()->willReturn(true);
        $libMessage->getContentType()->shouldBeCalled()->willReturn('contentType');
        $libMessage->getContentEncoding()->shouldBeCalled()->willReturn('contentEncoding');
        $libMessage->getType()->shouldBeCalled()->willReturn('type');
        $libMessage->getTimestamp()->shouldBeCalled()->willReturn(10101010);
        $libMessage->getPriority()->shouldBeCalled()->willReturn(5);
        $libMessage->getExpiration()->shouldBeCalled()->willReturn('2015-01-01 23:59:59');
        $libMessage->getUserId()->shouldBeCalled()->willReturn('userId');
        $libMessage->getAppId()->shouldBeCalled()->willReturn('appId');
        $libMessage->getMessageId()->shouldBeCalled()->willReturn('messageId');
        $libMessage->getReplyTo()->shouldBeCalled()->willReturn('replyTo');
        $libMessage->getCorrelationId()->shouldBeCalled()->willReturn('correlationId');
        $libMessage->getHeaders()->shouldBeCalled()->willReturn(['header1' => 'foo']);

        $ret = $mapper->toMessage($libMessage->reveal());

        static::assertInstanceOf(Message::class, $ret);

        $message->setBody('body')->shouldBeCalled();
        $message->setRoutingKey('routingKey')->shouldBeCalled();
        $message->setDeliveryTag('deliveryTag')->shouldBeCalled();
        $message->setDeliveryMode(2)->shouldBeCalled();
        $message->setExchangeName('exchangeName')->shouldBeCalled();
        $message->setRedelivered(true)->shouldBeCalled();
        $message->setContentType('contentType')->shouldBeCalled();
        $message->setContentEncoding('contentEncoding')->shouldBeCalled();
        $message->setType('type')->shouldBeCalled();
        $message->setDateTime((new \DateTime())->setTimestamp(10101010))->shouldBeCalled();
        $message->setPriority(5)->shouldBeCalled();
        $message->setExpiration(new \DateTime('2015-01-01 23:59:59'))->shouldBeCalled();
        $message->setUserId('userId')->shouldBeCalled();
        $message->setAppId('appId')->shouldBeCalled();
        $message->setMessageId('messageId')->shouldBeCalled();
        $message->setReplyTo('replyTo')->shouldBeCalled();
        $message->setCorrelationId('correlationId')->shouldBeCalled();
        $message->setHeaders(['header1' => 'foo'])->shouldBeCalled();
    }
}
