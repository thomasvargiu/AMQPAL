<?php

namespace AMQPAL\Adapter;

class MessageTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var Message;
     */
    protected $message;

    protected function setUp()
    {
        parent::setUp();

        $this->message = new Message();
    }

    public function testGetBody()
    {
        static::assertNull($this->message->getBody());
        $this->message->setBody('foo');
        static::assertEquals('foo', $this->message->getBody());
    }

    public function testGetRoutingKey()
    {
        static::assertNull($this->message->getRoutingKey());
        $this->message->setRoutingKey('foo');
        static::assertEquals('foo', $this->message->getRoutingKey());
    }

    public function testGetDeliveryTag()
    {
        static::assertNull($this->message->getDeliveryTag());
        $this->message->setDeliveryTag('foo');
        static::assertEquals('foo', $this->message->getDeliveryTag());
    }

    public function testGetDeliveryMode()
    {
        static::assertNull($this->message->getDeliveryMode());
        $this->message->setDeliveryMode(2);
        static::assertEquals(2, $this->message->getDeliveryMode());
    }

    public function testGetExchangeName()
    {
        static::assertNull($this->message->getExchangeName());
        $this->message->setExchangeName('foo');
        static::assertEquals('foo', $this->message->getExchangeName());
    }

    public function testIsRedelivered()
    {
        static::assertFalse($this->message->isRedelivered());
        $this->message->setRedelivered(true);
        static::assertEquals(true, $this->message->isRedelivered());
    }

    public function testGetContentType()
    {
        static::assertNull($this->message->getContentType());
        $this->message->setContentType('foo');
        static::assertEquals('foo', $this->message->getContentType());
    }

    public function testGetContentEncoding()
    {
        static::assertNull($this->message->getContentEncoding());
        $this->message->setContentEncoding('foo');
        static::assertEquals('foo', $this->message->getContentEncoding());
    }

    public function testGetType()
    {
        static::assertNull($this->message->getType());
        $this->message->setType('foo');
        static::assertEquals('foo', $this->message->getType());
    }

    public function testGetDateTime()
    {
        static::assertNull($this->message->getDateTime());
        $dateTime = new \DateTime();
        $this->message->setDateTime($dateTime);
        static::assertSame($dateTime, $this->message->getDateTime());
    }

    public function testGetPriority()
    {
        static::assertNull($this->message->getPriority());
        $this->message->setPriority(5);
        static::assertEquals(5, $this->message->getPriority());
    }

    public function testGetExpiration()
    {
        static::assertNull($this->message->getExpiration());
        $dateTime = new \DateTime();
        $this->message->setExpiration($dateTime);
        static::assertSame($dateTime, $this->message->getExpiration());
    }

    public function testGetUserId()
    {
        static::assertNull($this->message->getUserId());
        $this->message->setUserId('foo');
        static::assertEquals('foo', $this->message->getUserId());
    }

    public function testGetAppId()
    {
        static::assertNull($this->message->getAppId());
        $this->message->setAppId('foo');
        static::assertEquals('foo', $this->message->getAppId());
    }

    public function testGetMessageId()
    {
        static::assertNull($this->message->getMessageId());
        $this->message->setMessageId('foo');
        static::assertEquals('foo', $this->message->getMessageId());
    }

    public function testGetReplyTo()
    {
        static::assertNull($this->message->getReplyTo());
        $this->message->setReplyTo('foo');
        static::assertEquals('foo', $this->message->getReplyTo());
    }

    public function testGetCorrelationId()
    {
        static::assertNull($this->message->getCorrelationId());
        $this->message->setCorrelationId('foo');
        static::assertEquals('foo', $this->message->getCorrelationId());
    }

    public function testGetHeaders()
    {
        static::assertEquals([], $this->message->getHeaders());
        $this->message->setHeaders(['foo' => 'bar']);
        static::assertEquals(['foo' => 'bar'], $this->message->getHeaders());
    }
}
