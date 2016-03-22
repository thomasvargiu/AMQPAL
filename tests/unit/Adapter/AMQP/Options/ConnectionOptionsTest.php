<?php

namespace AMQPAL\Adapter\AMQP\Options;

class ConnectionOptionsTest extends \PHPUnit_Framework_TestCase
{
    public function testGetterAndSetter()
    {
        $configuration = [
            'host' => 'test-host',
            'port' => 1234,
            'username' => 'test-username',
            'password' => 'test-password',
            'vhost' => 'test-vhost',
            'read_timeout' => 12,
            'write_timeout' => 13,
            'connect_timeout' => 432,
            'heartbeat' => 234,
            'channel_max' => 16,
            'frame_max' => 16,
            'persistent' => true,
        ];

        $options = new ConnectionOptions();
        $options->setFromArray($configuration);

        static::assertEquals($configuration['host'], $options->getHost());
        static::assertEquals($configuration['port'], $options->getPort());
        static::assertEquals($configuration['username'], $options->getUsername());
        static::assertEquals($configuration['password'], $options->getPassword());
        static::assertEquals($configuration['vhost'], $options->getVhost());
        static::assertEquals($configuration['read_timeout'], $options->getReadTimeout());
        static::assertEquals($configuration['write_timeout'], $options->getWriteTimeout());
        static::assertEquals($configuration['heartbeat'], $options->getHeartbeat());
        static::assertEquals($configuration['connect_timeout'], $options->getConnectTimeout());
        static::assertEquals($configuration['channel_max'], $options->getChannelMax());
        static::assertEquals($configuration['frame_max'], $options->getFrameMax());
        static::assertEquals($configuration['persistent'], $options->isPersistent());
    }
}
