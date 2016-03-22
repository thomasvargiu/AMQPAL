<?php

namespace AMQPAL\Adapter\PhpAmqpLib\Factory;

use AMQPAL\Adapter\Exception;

class ConnectionFactoryFactoryTest extends \PHPUnit_Framework_TestCase
{

    public function testCreateFactory()
    {
        $factoryFactory = new ConnectionFactoryFactory();
        $factoryFactory->setFactoryMap([
            'stub' => ConnectionFactoryStub::class
        ]);
        
        static::assertInstanceOf(ConnectionFactoryStub::class, $factoryFactory->createFactory('stub'));
    }

    /**
     * @expectedException \AMQPAL\Adapter\Exception\InvalidArgumentException
     */
    public function testCreateFactoryNotExistingKey()
    {
        $factoryFactory = new ConnectionFactoryFactory();
        $factoryFactory->setFactoryMap([]);

        $factoryFactory->createFactory('stub');
    }

    /**
     * @expectedException \AMQPAL\Adapter\Exception\RuntimeException
     */
    public function testCreateFactoryWithInvalidFactory()
    {
        $factoryFactory = new ConnectionFactoryFactory();
        $factoryFactory->setFactoryMap(['foo' => 'ArrayObject']);

        $factoryFactory->createFactory('foo');
    }
}
