# AMQPAL

[![Build Status](https://scrutinizer-ci.com/g/thomasvargiu/AMQPAL/badges/build.png?b=master)](https://scrutinizer-ci.com/g/thomasvargiu/AMQPAL/build-status/master)
[![Code Coverage](https://scrutinizer-ci.com/g/thomasvargiu/AMQPAL/badges/coverage.png?b=master)](https://scrutinizer-ci.com/g/thomasvargiu/AMQPAL/?branch=master)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/thomasvargiu/AMQPAL/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/thomasvargiu/AMQPAL/?branch=master)
[![Latest Stable Version](https://poser.pugx.org/thomasvargiu/AMQPAL/v/stable)](https://packagist.org/packages/thomasvargiu/AMQPAL)
[![Total Downloads](https://poser.pugx.org/thomasvargiu/AMQPAL/downloads)](https://packagist.org/packages/thomasvargiu/AMQPAL)
[![Latest Unstable Version](https://poser.pugx.org/thomasvargiu/AMQPAL/v/unstable)](https://packagist.org/packages/thomasvargiu/AMQPAL)
[![License](https://poser.pugx.org/thomasvargiu/rabbitmq-module/license)](https://packagist.org/packages/thomasvargiu/AMQPAL)

## AMQP Abstraction Layer

An abstraction layer to use different adapters.

Supported adapters:

- [php-amqplib](https://github.com/php-amqplib/php-amqplib) (`phpamqplib` in the factory)
- [php-amqp extension](https://github.com/pdezwart/php-amqp) (`amqp` in the factory)

### Example

```php

use AMQPAL\Adapter;
use AMQPAL\Options;

$options = [
    'name' => 'amqp', // or phpamqplib
    'options' => [
        'host' => 'localhost',
        'username' => 'guest',
        'password' => 'guest',
        'vhost' => '/'
    ]
];

$factory = new Adapter\AdapterFactory();
$adapter = $factory->createAdapter($options);

$channel = $adapter->createChannel();

/*
 * Creating exchange...
 */
$exchangeOptions = new Options\ExchangeOptions([
    'name' => 'exchange-name',
    'type' => 'direct'
]);

$exchange = $channel->createExchange($exchangeOptions);

// or:
$exchange = $channel->createExchange([
    'name' => 'exchange-name',
    'type' => 'direct'
]);

/*
 * Creating queue...
 */
$queueOptions = new Options\QueueOptions([
    'name' => 'queue-name',
]);

$queue = $channel->createQueue($queueOptions);

// or:
$queue = $channel->createQueue([
    'name' => 'queue-name',
]);

$queue->declareQueue();
$queue->bind('exchange-name');

// publishing a message...
$exchange->publish('my message in the queue');

// get the next message in the queue...
$message = $queue->get();



// or consuming a queue...
$callback = function (Adapter\Message $message, Adapter\QueueInterface $queue) {
    // ack the message...
    $queue->ack($message->getDeliveryTag());
    
    // return false to stop consuming...
    return false;
};

// set channel qos to fetch just one message at time
$channel->setQos(null, 1);
// and consuming...
$queue->consume($callback); // This is a blocking function

```
