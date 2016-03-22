<?php

namespace AMQPAL\Options;

class QueueOptionsTest extends \PHPUnit_Framework_TestCase
{
    public function testOptions()
    {
        $configuration = [
            'name' => 'test-name',
            'passive' => true,
            'durable' => true,
            'auto_delete' => false,
            'exclusive' => true,
            'no_wait' => true,
            'arguments' => [
                'argument1' => 'value1',
            ],
            'routing_keys' => [
                'routing1',
                'routing2',
            ],
        ];
        $options = new QueueOptions();
        $options->setFromArray($configuration);

        static::assertEquals($configuration['name'], $options->getName());
        static::assertEquals($configuration['passive'], $options->isPassive());
        static::assertEquals($configuration['durable'], $options->isDurable());
        static::assertEquals($configuration['auto_delete'], $options->isAutoDelete());
        static::assertEquals($configuration['exclusive'], $options->isExclusive());
        static::assertEquals($configuration['no_wait'], $options->isNoWait());
        static::assertEquals($configuration['arguments'], $options->getArguments());
        static::assertEquals($configuration['routing_keys'], $options->getRoutingKeys());
    }
}
