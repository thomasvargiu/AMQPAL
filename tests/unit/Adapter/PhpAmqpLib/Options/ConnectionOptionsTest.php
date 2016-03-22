<?php

namespace AMQPAL\Adapter\PhpAmqpLib\Options;

use AMQPAL\Adapter\PhpAmqpLib\Factory;

class ConnectionOptionsTest extends \PHPUnit_Framework_TestCase
{
    public function testGetterAndSetter()
    {
        $configuration = [
            'type' => 'test-type',
            'host' => 'test-host',
            'port' => 1234,
            'username' => 'test-username',
            'password' => 'test-password',
            'vhost' => 'test-vhost',
            'insist' => true,
            'login_method' => 'test-login_method',
            'locale' => 'test-locale',
            'read_write_timeout' => 12,
            'keep_alive' => true,
            'heartbeat' => 234,
            'connection_timeout' => 432,
            'ssl_options' => [
                'opt1' => 'value1',
                'opt2' => 'value2',
            ],
        ];

        $options = new ConnectionOptions();
        $options->setFromArray($configuration);

        static::assertEquals($configuration['type'], $options->getType());
        static::assertEquals($configuration['host'], $options->getHost());
        static::assertEquals($configuration['port'], $options->getPort());
        static::assertEquals($configuration['username'], $options->getUsername());
        static::assertEquals($configuration['password'], $options->getPassword());
        static::assertEquals($configuration['vhost'], $options->getVhost());
        static::assertEquals($configuration['insist'], $options->isInsist());
        static::assertEquals($configuration['login_method'], $options->getLoginMethod());
        static::assertEquals($configuration['locale'], $options->getLocale());
        static::assertEquals($configuration['read_write_timeout'], $options->getReadWriteTimeout());
        static::assertEquals($configuration['keep_alive'], $options->isKeepAlive());
        static::assertEquals($configuration['heartbeat'], $options->getHeartbeat());
        static::assertEquals($configuration['connection_timeout'], $options->getConnectionTimeout());
        static::assertEquals($configuration['ssl_options'], $options->getSslOptions());
        static::assertInstanceOf(Factory\ConnectionFactoryFactory::class, $options->getConnectionFactoryFactory());
    }

    public function testSetConnectionFactory()
    {
        $connectionFactoryFactory = $this->prophesize(Factory\ConnectionFactoryFactory::class);
        $options = new ConnectionOptions();
        $options->setConnectionFactoryFactory($connectionFactoryFactory->reveal());

        static::assertSame($connectionFactoryFactory->reveal(), $options->getConnectionFactoryFactory());
    }
}
