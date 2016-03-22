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

- [php-amqplib](https://github.com/php-amqplib/php-amqplib)
- [php-amqp extension](https://github.com/pdezwart/php-amqp)

### Example

```php

use AMQPAL\Adapter\AMQP\AMQP;
use AMQP\Options;

$connectionOptions = [];
$adapter = new AMQP($connectionOptions);
$channel = $adapter->createChannel();

$exchangeOptions = new Options\ExchangeOptions([
    'name' => 'exchange-name',
    'type' => 'direct'
]);

$exchange = $channel->createExchange($exchangeOptions);

$queueOptions = new Options\QueueOptions([
    'name' => 'queue-name',
]);

$queue = $channel->createQueue($queueOptions);

$queue->declareQueue();
$queue->bind('exchange-name');

$message = $queue->get();

```
