<?php

namespace AMQPAL\Adapter;

class AdapterFactoryTest extends \PHPUnit_Framework_TestCase
{
    public function testSetAdapters()
    {
        $adapters = [
            'foo' => AdapterStub::class,
            'bar' => AdapterStub::class,
        ];

        $factory = new AdapterFactory();
        $factory->setAdapters($adapters);

        static::assertEquals($adapters, $factory->getAdapters());
    }

    public function testAddAdapter()
    {
        $factory = new AdapterFactory();
        $factory->setAdapters([]);

        static::assertEquals([], $factory->getAdapters());

        $factory->setAdapter('foo', AdapterStub::class);
        static::assertEquals(['foo' => AdapterStub::class], $factory->getAdapters());
    }

    public function testCreateAdapter()
    {
        $adapters = [
            'foo' => AdapterStub::class,
            'bar' => AdapterStub::class,
        ];

        $factory = new AdapterFactory();
        $factory->setAdapters($adapters);

        static::assertEquals($adapters, $factory->getAdapters());

        $options = [
            'name' => 'foo',
            'options' => [
                'host' => 'foo-hostname'
            ]
        ];
        $adapter = $factory->createAdapter($options);

        static::assertInstanceOf(AdapterStub::class, $adapter);
        static::assertEquals($adapter->options, ['host' => 'foo-hostname']);
    }

    /**
     * @expectedException \AMQPAL\Adapter\Exception\InvalidArgumentException
     * @expectedExceptionMessage Unable to find 'name' key.
     */
    public function testCreateAdapterWithNoName()
    {
        $adapters = [];

        $factory = new AdapterFactory();
        $factory->setAdapters($adapters);

        $options = [
            'options' => [

            ]
        ];
        $factory->createAdapter($options);
    }

    /**
     * @expectedException \AMQPAL\Adapter\Exception\InvalidArgumentException
     * @expectedExceptionMessage Unable to find 'options' key.
     */
    public function testCreateAdapterWithNoOptions()
    {
        $adapters = [];

        $factory = new AdapterFactory();
        $factory->setAdapters($adapters);

        $options = [
            'name' => 'foo',
        ];
        $factory->createAdapter($options);
    }

    /**
     * @expectedException \AMQPAL\Adapter\Exception\OutOfBoundsException
     * @expectedExceptionMessage Unable to find adapter 'foo'.
     */
    public function testCreateAdapterWithInvalidAdapter()
    {
        $adapters = [];

        $factory = new AdapterFactory();
        $factory->setAdapters($adapters);

        $options = [
            'name' => 'foo',
            'options' => []
        ];
        $factory->createAdapter($options);
    }
}
