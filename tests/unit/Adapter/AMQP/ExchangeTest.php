<?php

namespace AMQPAL\Adapter\AMQP;

use Prophecy\Prophecy\MethodProphecy;
use AMQPAL\Options;
use Prophecy\Argument;

class ExchangeTest extends \PHPUnit_Framework_TestCase
{

    public function testSetResource()
    {
        $resource = $this->prophesize(\AMQPExchange::class);

        $exchange = new Exchange();
        $exchange->setResource($resource->reveal());

        static::assertSame($resource->reveal(), $exchange->getResource());
    }

    public function testSetOptions()
    {
        $options = $this->getDefaultOptionsProphet();
        $resource = $this->prophesize(\AMQPExchange::class);

        $resource->setFlags(AMQP_NOPARAM | AMQP_DURABLE | AMQP_PASSIVE | AMQP_AUTODELETE | AMQP_INTERNAL | AMQP_NOWAIT)
            ->shouldBeCalled();
        $resource->setType('exchangeType')->shouldBeCalled();
        $resource->setName('exchangeName')->shouldBeCalled();
        $resource->setArguments(['arg1' => 'value1'])->shouldBeCalled();


        $exchange = new Exchange();
        $exchange->setResource($resource->reveal());

        static::assertSame($exchange, $exchange->setOptions($options->reveal()));
        static::assertSame($options->reveal(), $exchange->getOptions());
    }

    public function testDeclareExchange()
    {
        $options = $this->getDefaultOptionsProphet();
        $resource = $this->prophesize(\AMQPExchange::class);

        $resource->setFlags(AMQP_NOPARAM | AMQP_DURABLE | AMQP_PASSIVE | AMQP_AUTODELETE | AMQP_INTERNAL | AMQP_NOWAIT)
            ->shouldBeCalled();
        $resource->setType('exchangeType')->shouldBeCalled();
        $resource->setName('exchangeName')->shouldBeCalled();
        $resource->setArguments(['arg1' => 'value1'])->shouldBeCalled();

        $options->isDeclare()->willReturn(true);

        $resource->declareExchange()->shouldBeCalled();

        $exchange = new Exchange();
        $exchange->setResource($resource->reveal());
        $exchange->setOptions($options->reveal());

        static::assertSame($exchange, $exchange->declareExchange());
    }

    public function testDeclareExchangeWithNoDeclare()
    {
        $options = $this->getDefaultOptionsProphet();
        $resource = $this->prophesize(\AMQPExchange::class);

        $resource->setFlags(AMQP_NOPARAM | AMQP_DURABLE | AMQP_PASSIVE | AMQP_AUTODELETE | AMQP_INTERNAL | AMQP_NOWAIT)
            ->shouldBeCalled();
        $resource->setType('exchangeType')->shouldBeCalled();
        $resource->setName('exchangeName')->shouldBeCalled();
        $resource->setArguments(['arg1' => 'value1'])->shouldBeCalled();

        $options->isDeclare()->willReturn(false);

        $resource->declareExchange()->shouldNotBeCalled();

        $exchange = new Exchange();
        $exchange->setResource($resource->reveal());
        $exchange->setOptions($options->reveal());

        static::assertSame($exchange, $exchange->declareExchange());
    }

    /**
     * @return \Prophecy\Prophecy\ObjectProphecy
     */
    protected function getDefaultOptionsProphet()
    {
        $options = $this->prophesize(Options\ExchangeOptions::class);

        $options->isDurable()->willReturn(true);
        $options->isPassive()->willReturn(true);
        $options->isAutoDelete()->willReturn(true);
        $options->isInternal()->willReturn(true);
        $options->isNoWait()->willReturn(true);
        $options->getType()->willReturn('exchangeType');
        $options->getName()->willReturn('exchangeName');
        $options->getArguments()->willReturn(['arg1' => 'value1']);

        return $options;
    }

    /**
     * @dataProvider deleteProvider()
     */
    public function testDelete($ifUnused, $noWait, $flags)
    {
        $options = $this->getDefaultOptionsProphet();
        $resource = $this->prophesize(\AMQPExchange::class);

        $resource->setFlags(AMQP_NOPARAM | AMQP_DURABLE | AMQP_PASSIVE | AMQP_AUTODELETE | AMQP_INTERNAL | AMQP_NOWAIT)
            ->shouldBeCalled();
        $resource->setType('exchangeType')->shouldBeCalled();
        $resource->setName('exchangeName')->shouldBeCalled();
        $resource->setArguments(['arg1' => 'value1'])->shouldBeCalled();
        $options->isDeclare()->willReturn(false);

        $resource->delete('exchangeName', $flags)->shouldBeCalled();

        $exchange = new Exchange();
        $exchange->setResource($resource->reveal());
        $exchange->setOptions($options->reveal());

        static::assertSame($exchange, $exchange->delete($ifUnused, $noWait));
    }

    public function testBind()
    {
        $arguments = ['arg2' => 'value2'];

        $options = $this->getDefaultOptionsProphet();
        $resource = $this->prophesize(\AMQPExchange::class);

        $resource->setFlags(AMQP_NOPARAM | AMQP_DURABLE | AMQP_PASSIVE | AMQP_AUTODELETE | AMQP_INTERNAL | AMQP_NOWAIT)
            ->shouldBeCalled();
        $resource->setType('exchangeType')->shouldBeCalled();
        $resource->setName('exchangeName')->shouldBeCalled();
        $resource->setArguments(['arg1' => 'value1'])->shouldBeCalled();
        $options->isDeclare()->willReturn(false);

        $resource->bind('exchange2bindName', 'routingKey', $arguments)->shouldBeCalled();

        $exchange = new Exchange();
        $exchange->setResource($resource->reveal());
        $exchange->setOptions($options->reveal());

        static::assertSame($exchange, $exchange->bind('exchange2bindName', 'routingKey', false, $arguments));
    }

    public function testUnbind()
    {
        $arguments = ['arg2' => 'value2'];

        $options = $this->getDefaultOptionsProphet();
        $resource = $this->prophesize(\AMQPExchange::class);

        $resource->setFlags(AMQP_NOPARAM | AMQP_DURABLE | AMQP_PASSIVE | AMQP_AUTODELETE | AMQP_INTERNAL | AMQP_NOWAIT)
            ->shouldBeCalled();
        $resource->setType('exchangeType')->shouldBeCalled();
        $resource->setName('exchangeName')->shouldBeCalled();
        $resource->setArguments(['arg1' => 'value1'])->shouldBeCalled();
        $options->isDeclare()->willReturn(false);

        $resource->unbind('exchange2bindName', 'routingKey', $arguments)->shouldBeCalled();

        $exchange = new Exchange();
        $exchange->setResource($resource->reveal());
        $exchange->setOptions($options->reveal());

        static::assertSame($exchange, $exchange->unbind('exchange2bindName', 'routingKey', $arguments));
    }

    /**
     * @dataProvider publishProvider()
     */
    public function testPublish($message, $routingKey, $mandatory, $immediate, $attributes, $flags)
    {
        $options = $this->getDefaultOptionsProphet();
        $resource = $this->prophesize(\AMQPExchange::class);

        $resource->setFlags(AMQP_NOPARAM | AMQP_DURABLE | AMQP_PASSIVE | AMQP_AUTODELETE | AMQP_INTERNAL | AMQP_NOWAIT)
            ->shouldBeCalled();
        $resource->setType('exchangeType')->shouldBeCalled();
        $resource->setName('exchangeName')->shouldBeCalled();
        $resource->setArguments(['arg1' => 'value1'])->shouldBeCalled();
        $options->isDeclare()->willReturn(false);

        $resource->publish($message, $routingKey, $flags, $attributes)->shouldBeCalled();

        $exchange = new Exchange();
        $exchange->setResource($resource->reveal());
        $exchange->setOptions($options->reveal());

        static::assertSame($exchange, $exchange->publish($message, $routingKey, $mandatory, $immediate, $attributes));
    }

    public function testSetAndGetChannel()
    {
        $channel = $this->prophesize(Channel::class);

        $exchange = new Exchange();
        static::assertSame($exchange, $exchange->setChannel($channel->reveal()));
        static::assertSame($channel->reveal(), $exchange->getChannel());
    }

    public function testGetConnection()
    {
        $channel = $this->prophesize(Channel::class);
        $connection = $this->prophesize(Connection::class);

        $channel->getConnection()->willReturn($connection);

        $exchange = new Exchange();
        static::assertSame($exchange, $exchange->setChannel($channel->reveal()));
        static::assertSame($connection->reveal(), $exchange->getConnection());
    }

    public function deleteProvider()
    {
        return [
            [true, true, AMQP_IFUNUSED | AMQP_NOWAIT],
            [true, false, AMQP_IFUNUSED],
            [false, true, AMQP_NOWAIT],
            [false, false, AMQP_NOPARAM],
        ];
    }

    public function publishProvider()
    {
        return [
            ['message', 'routingKey', true, true, ['attr1' => 'value1'], AMQP_MANDATORY | AMQP_IMMEDIATE],
            ['message', 'routingKey', true, false, ['attr1' => 'value1'], AMQP_MANDATORY],
            ['message', 'routingKey', false, true, ['attr1' => 'value1'], AMQP_IMMEDIATE],
            ['message', 'routingKey', false, false, ['attr1' => 'value1'], AMQP_NOPARAM],
        ];
    }
}
