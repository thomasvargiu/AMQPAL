<?php

namespace AMQPAL\Adapter\PhpAmqpLib;

use PhpAmqpLib\Message\AMQPMessage;
use Prophecy\Argument;
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
        $libMessage = $this->prophesize(AMQPMessage::class);
        $message = $this->prophesize(Message::class);

        $mapper = new MessageMapper();
        $mapper->setMessagePrototype($message->reveal());

        $libMessage->has(Argument::any())->shouldBeCalled()->willReturn(true);

        $libMessage->getBody()->shouldBeCalled()->willReturn('body');
        $libMessage->get('routing_key')->shouldBeCalled()->willReturn('routingKey');
        $libMessage->get('delivery_tag')->shouldBeCalled()->willReturn('deliveryTag');
        $libMessage->get('delivery_mode')->shouldBeCalled()->willReturn(2);
        $libMessage->get('exchange')->shouldBeCalled()->willReturn('exchangeName');
        $libMessage->get('redelivered')->shouldBeCalled()->willReturn(true);
        $libMessage->get('content_type')->shouldBeCalled()->willReturn('contentType');
        $libMessage->get('content_encoding')->shouldBeCalled()->willReturn('contentEncoding');
        $libMessage->get('type')->shouldBeCalled()->willReturn('type');
        $libMessage->get('timestamp')->shouldBeCalled()->willReturn(10101010);
        $libMessage->get('priority')->shouldBeCalled()->willReturn(5);
        $libMessage->get('expiration')->shouldBeCalled()->willReturn('2015-01-01 23:59:59');
        $libMessage->get('user_id')->shouldBeCalled()->willReturn('userId');
        $libMessage->get('app_id')->shouldBeCalled()->willReturn('appId');
        $libMessage->get('message_id')->shouldBeCalled()->willReturn('messageId');
        $libMessage->get('reply_to')->shouldBeCalled()->willReturn('replyTo');
        $libMessage->get('correlation_id')->shouldBeCalled()->willReturn('correlationId');
        $libMessage->get('application_headers')->shouldBeCalled()->willReturn(['header1' => 'foo']);

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
