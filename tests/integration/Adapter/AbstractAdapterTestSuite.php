<?php

namespace AMQPAL\IntegrationTest\Adapter;

use AMQPAL\Adapter\AdapterInterface;
use AMQPAL\IntegrationTest\Exception\TimeoutException;
use RabbitMq\ManagementApi\Client;
use AMQPAL\Adapter\ChannelInterface;
use AMQPAL\Adapter\Message;
use AMQPAL\Options\ExchangeOptions;
use AMQPAL\Options\QueueOptions;

abstract class AbstractAdapterTestSuite extends \PHPUnit_Framework_TestCase
{
    /**
     * @var array
     */
    protected $connectionOptions = [
        'host' => '192.168.2.128',
        'port' => 5679,
        'username' => 'guest',
        'password' => 'guest',
        'vhost' => 'test'
    ];

    /**
     * @var string
     */
    protected $managementHost = '192.168.2.128';
    /**
     * @var int
     */
    protected $managementPort = 15679;

    /**
     * @var AdapterInterface
     */
    protected $adapter;
    /**
     * @var Client
     */
    protected $client;


    public function setUp()
    {
        parent::setUp();

        $this->client =  new Client(null, sprintf('http://%s:%d', $this->managementHost, $this->managementPort));

        $this->cleanAll($this->client);
    }

    /**
     * @dataProvider createExchangeProvider
     * @param array $exchangeOptionsArray
     */
    public function testCreateExchange(array $exchangeOptionsArray)
    {
        $channel = $this->adapter->createChannel();
        static::assertInstanceOf(ChannelInterface::class, $channel);

        static::assertCount(1, $this->client->channels()->all());

        $exchangeOptions = new ExchangeOptions($exchangeOptionsArray);

        $exchange = $channel->createExchange($exchangeOptions);
        $exchange->declareExchange();

        $exchangeInfo = $this->client->exchanges()->get('test', 'exchange-name');

        static::assertInternalType('array', $exchangeInfo);
        static::assertEquals($exchangeOptionsArray['name'], $exchangeInfo['name']);
        static::assertEquals($exchangeOptionsArray['type'], $exchangeInfo['type']);
        static::assertEquals($exchangeOptionsArray['durable'], $exchangeInfo['durable']);
        static::assertEquals($exchangeOptionsArray['auto_delete'], $exchangeInfo['auto_delete']);
        static::assertEquals($exchangeOptionsArray['internal'], $exchangeInfo['internal']);
        static::assertEquals($exchangeOptionsArray['arguments'], $exchangeInfo['arguments']);
    }

    /**
     * @dataProvider createQueueProvider
     * @param array $queueOptionsArray
     */
    public function testCreateQueue(array $queueOptionsArray)
    {
        $channel = $this->adapter->createChannel();
        static::assertInstanceOf(ChannelInterface::class, $channel);

        static::assertCount(1, $this->client->channels()->all());

        $queueOptions = new QueueOptions($queueOptionsArray);

        $queue = $channel->createQueue($queueOptions);
        $queue->declareQueue();

        $queueInfo = $this->client->queues()->get('test', 'queue-name');

        static::assertInternalType('array', $queueInfo);
        static::assertEquals($queueOptionsArray['name'], $queueInfo['name']);
        static::assertEquals($queueOptionsArray['durable'], $queueInfo['durable']);
        static::assertEquals($queueOptionsArray['auto_delete'], $queueInfo['auto_delete']);
        static::assertEquals($queueOptionsArray['exclusive'], $queueInfo['exclusive']);
        static::assertEquals($queueOptionsArray['arguments'], $queueInfo['arguments']);
    }

    public function testPublishAndGet()
    {
        $channel = $this->adapter->createChannel();
        $exchangeOptions = new ExchangeOptions([
            'name' => 'exchange-name',
            'type' => 'direct'
        ]);
        $queueOptions = new QueueOptions([
            'name' => 'queue-name',
        ]);

        $exchange = $channel->createExchange($exchangeOptions);
        $exchange->declareExchange();

        $queue = $channel->createQueue($queueOptions);
        $queue->declareQueue();

        $queue->bind('exchange-name');

        $exchange->publish('foo');

        $message = $this->doUntil(
            function () use ($queue) {
                return $queue->get();
            },
            function ($ret) {
                return null !== $ret;
            },
            5,
            10000
        );

        static::assertInstanceOf(Message::class, $message);
        static::assertEquals('foo', $message->getBody());

        $client = $this->client;
        $queueInfo = $this->doUntil(
            function () use ($client) {
                return $client->queues()->get('test', 'queue-name');
            },
            function ($ret) {
                return is_array($ret)
                && 1 === $ret['messages']
                && 1 === $ret['messages_unacknowledged'];
            },
            5,
            10000
        );

        static::assertEquals(1, $queueInfo['messages']);
        static::assertEquals(1, $queueInfo['messages_unacknowledged']);
    }

    public function testPublishAndGetWithAutoAck()
    {
        $channel = $this->adapter->createChannel();
        $exchangeOptions = new ExchangeOptions([
            'name' => 'exchange-name',
            'type' => 'direct'
        ]);
        $queueOptions = new QueueOptions([
            'name' => 'queue-name',
        ]);

        $exchange = $channel->createExchange($exchangeOptions);
        $exchange->declareExchange();

        $queue = $channel->createQueue($queueOptions);
        $queue->declareQueue();

        $queue->bind('exchange-name');

        $exchange->publish('foo');

        $message = $this->doUntil(
            function () use ($queue) {
                return $queue->get(true);
            },
            function ($ret) {
                return null !== $ret;
            },
            10,
            10000
        );

        static::assertInstanceOf(Message::class, $message);
        static::assertEquals('foo', $message->getBody());

        $client = $this->client;

        $queueInfo = $this->doUntil(
            function () use ($client) {
                return $client->queues()->get('test', 'queue-name');
            },
            function ($ret) {
                return is_array($ret)
                && 0 === $ret['messages']
                && 0 === $ret['messages_unacknowledged'];
            },
            10,
            10000
        );

        static::assertEquals(0, $queueInfo['messages']);
        static::assertEquals(0, $queueInfo['messages_unacknowledged']);
    }

    /**
     * @param callable $doFunction
     * @param callable $until
     * @param float  $timeout
     * @param null     $usleep
     * @return mixed
     */
    public function doUntil(callable $doFunction, callable $until, $timeout, $usleep = null)
    {
        $startTime = microtime(true);
        while (true) {
            $ret = $doFunction();
            $valid = $until($ret);
            if ($valid) {
                return $ret;
            }
            if (microtime(true) - $startTime > $timeout) {
                throw new TimeoutException('Timeout');
            } elseif ($usleep) {
                usleep($usleep);
            }
        }
    }

    public function createExchangeProvider()
    {
        return [
            [
                [
                    'name' => 'exchange-name',
                    'type' => 'fanout',
                    'passive' => false,
                    'durable' => false,
                    'auto_delete' => true,
                    'internal' => false,
                    'no_wait' => false,
                    'arguments' => []
                ],
                [
                    'name' => 'exchange-name',
                    'type' => 'fanout',
                    'passive' => false,
                    'durable' => true,
                    'auto_delete' => false,
                    'internal' => true,
                    'no_wait' => true,
                    'arguments' => ['foo' => 'bar']
                ]
            ]
        ];
    }

    public function createQueueProvider()
    {
        return [
            [
                [
                    'name' => 'queue-name',
                    'passive' => false,
                    'exclusive' => false,
                    'durable' => false,
                    'auto_delete' => true,
                    'arguments' => []
                ],
                [
                    'name' => 'queue-name',
                    'passive' => false,
                    'exclusive' => true,
                    'durable' => true,
                    'auto_delete' => false,
                    'arguments' => ['foo' => 'bar']
                ]
            ]
        ];
    }

    /**
     * Clean rabbitmq
     *
     * @param Client $client
     */
    protected function cleanAll(Client $client)
    {
        $exchanges = $client->exchanges()->all();
        foreach ($exchanges as $exchange) {
            if ('' === $exchange['name'] || 0 === strpos($exchange['name'], 'amq')) {
                continue;
            }
            $client->exchanges()->delete($exchange['vhost'], $exchange['name']);
        }

        $queues = $client->queues()->all();
        foreach ($queues as $queue) {
            $client->queues()->delete($queue['vhost'], $queue['name']);
        }
    }

    public function tearDown()
    {
        try {
            $this->adapter->getConnection()->disconnect();
        } catch (\Exception $e) {
            // ignore
        }

        parent::tearDown();
    }
}
