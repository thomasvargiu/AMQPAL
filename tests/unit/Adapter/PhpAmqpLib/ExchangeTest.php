<?php

namespace AMQPAL\Adapter\PhpAmqpLib;

use PhpAmqpLib\Channel\AMQPChannel;
use PhpAmqpLib\Message\AMQPMessage;
use AMQPAL\Options;
use Prophecy\Argument;

class ExchangeTest extends \PHPUnit_Framework_TestCase
{

    public function testSetOptions()
    {
        $options = $this->getDefaultOptionsProphet();

        $exchange = new Exchange();

        static::assertSame($exchange, $exchange->setOptions($options->reveal()));
        static::assertSame($options->reveal(), $exchange->getOptions());
    }

    public function testSetOptionsWithArray()
    {
        $options = ['name' => 'exchangeName', 'type' => 'exchangeType'];

        $exchange = new Exchange();

        static::assertSame($exchange, $exchange->setOptions($options));
        $exchangeOptions = $exchange->getOptions();
        static::assertInstanceOf(Options\ExchangeOptions::class, $exchangeOptions);
        static::assertEquals('exchangeName', $exchangeOptions->getName());
    }

    public function testDeclareExchange()
    {
        $adapterChannel = $this->prophesize(AMQPChannel::class);
        $channel = $this->prophesize(Channel::class);
        $options = $this->getDefaultOptionsProphet();

        $channel->getResource()->willReturn($adapterChannel);
        $adapterChannel->exchange_declare(
            'exchangeName',
            'exchangeType',
            true,
            true,
            true,
            true,
            true,
            ['arg1' => 'value1']
        )->shouldBeCalled();

        $exchange = new Exchange();
        $exchange->setChannel($channel->reveal());
        $exchange->setOptions($options->reveal());

        static::assertSame($exchange, $exchange->declareExchange());
    }

    public function testDeclareExchangeWithNoDeclare()
    {
        $adapterChannel = $this->prophesize(AMQPChannel::class);
        $channel = $this->prophesize(Channel::class);
        $options = $this->getDefaultOptionsProphet();

        $channel->getResource()->willReturn($adapterChannel);

        $exchange = new Exchange();
        $exchange->setChannel($channel->reveal());
        $exchange->setOptions($options->reveal());

        static::assertSame($exchange, $exchange->declareExchange());
    }

    /**
     * @return \Prophecy\Prophecy\ObjectProphecy|Options\ExchangeOptions
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
    public function testDelete($ifUnused, $noWait)
    {
        $adapterChannel = $this->prophesize(AMQPChannel::class);
        $channel = $this->prophesize(Channel::class);
        $options = $this->getDefaultOptionsProphet();

        $adapterChannel->exchange_delete('exchangeName', $ifUnused, $noWait)->shouldBeCalled();
        $channel->getResource()->willReturn($adapterChannel);

        $exchange = new Exchange();
        $exchange->setChannel($channel->reveal());
        $exchange->setOptions($options->reveal());

        static::assertSame($exchange, $exchange->delete($ifUnused, $noWait));
    }

    public function testBind()
    {
        $arguments = ['arg2' => 'value2'];
        $adapterChannel = $this->prophesize(AMQPChannel::class);

        $channel = $this->prophesize(Channel::class);
        $options = $this->getDefaultOptionsProphet();

        $adapterChannel->exchange_bind('exchangeName', 'exchange2bindName', 'routingKey', false, $arguments)
            ->shouldBeCalled();
        $channel->getResource()->willReturn($adapterChannel);

        $exchange = new Exchange();
        $exchange->setChannel($channel->reveal());
        $exchange->setOptions($options->reveal());

        static::assertSame($exchange, $exchange->bind('exchange2bindName', 'routingKey', false, $arguments));
    }

    public function testBindWithNullRoutingKey()
    {
        $arguments = ['arg2' => 'value2'];

        $adapterChannel = $this->prophesize(AMQPChannel::class);

        $channel = $this->prophesize(Channel::class);
        $options = $this->getDefaultOptionsProphet();

        $adapterChannel->exchange_bind('exchangeName', 'exchange2bindName', '', false, $arguments)
            ->shouldBeCalled();
        $channel->getResource()->willReturn($adapterChannel);

        $exchange = new Exchange();
        $exchange->setChannel($channel->reveal());
        $exchange->setOptions($options->reveal());

        static::assertSame($exchange, $exchange->bind('exchange2bindName', null, false, $arguments));
    }

    public function testUnbind()
    {
        $arguments = ['arg2' => 'value2'];

        $adapterChannel = $this->prophesize(AMQPChannel::class);

        $channel = $this->prophesize(Channel::class);
        $options = $this->getDefaultOptionsProphet();

        $adapterChannel->exchange_unbind('exchangeName', 'exchange2bindName', 'routingKey', $arguments)
            ->shouldBeCalled();
        $channel->getResource()->willReturn($adapterChannel);

        $exchange = new Exchange();
        $exchange->setChannel($channel->reveal());
        $exchange->setOptions($options->reveal());

        static::assertSame($exchange, $exchange->unbind('exchange2bindName', 'routingKey', $arguments));
    }

    public function testUnbindWithNullRoutingKey()
    {
        $arguments = ['arg2' => 'value2'];

        $adapterChannel = $this->prophesize(AMQPChannel::class);

        $channel = $this->prophesize(Channel::class);
        $options = $this->getDefaultOptionsProphet();

        $adapterChannel->exchange_unbind('exchangeName', 'exchange2bindName', '', $arguments)
            ->shouldBeCalled();
        $channel->getResource()->willReturn($adapterChannel);

        $exchange = new Exchange();
        $exchange->setChannel($channel->reveal());
        $exchange->setOptions($options->reveal());

        static::assertSame($exchange, $exchange->unbind('exchange2bindName', null, $arguments));
    }

    /**
     * @dataProvider publishProvider()
     */
    public function testPublish($message, $routingKey, $mandatory, $immediate, $attributes)
    {

        $adapterChannel = $this->prophesize(AMQPChannel::class);

        $channel = $this->prophesize(Channel::class);
        $options = $this->getDefaultOptionsProphet();

        $adapterChannel->basic_publish(Argument::that(function (AMQPMessage $msgObject) use ($message) {
            return $msgObject instanceof AMQPMessage &&
            $msgObject->getBody() === $message &&
            $msgObject->get('delivery_mode') == 5;
        }), 'exchangeName', $routingKey ?: '', $mandatory, $immediate)
            ->shouldBeCalled();
        $channel->getResource()->willReturn($adapterChannel);

        $exchange = new Exchange();
        $exchange->setChannel($channel->reveal());
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
            [true, true],
            [true, false],
            [false, true],
            [false, false],
        ];
    }

    public function publishProvider()
    {
        return [
            ['message', 'routingKey', true, true, ['delivery_mode' => 5]],
            ['message', null, true, false, ['delivery_mode' => 5]],
            ['message', 'routingKey', false, true, ['delivery_mode' => 5]],
            ['message', 'routingKey', false, false, ['delivery_mode' => 5]],
        ];
    }
}
