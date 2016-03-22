# AMQPAL

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
$queue->bind('exchange-name);

$message = $queue->get();

```
