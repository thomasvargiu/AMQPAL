<?php

namespace AMQPAL\Adapter\PhpAmqpLib\Factory;

use AMQPAL\Adapter\Exception;

/**
 * Class ConnectionFactoryFactory
 *
 * @package AMQPAL\Adapter\PhpAmqpLib\Factory
 */
class ConnectionFactoryFactory
{
    /**
     * @var array
     */
    protected $factoryMap = [
        'lazy' => LazyConnectionFactory::class,
        'stream' => StreamConnectionFactory::class,
        'socket' => SocketConnectionFactory::class,
        'ssl' => SSLConnectionFactory::class,
    ];
    
    protected $instances = [];

    /**
     * Create the connection factory
     *
     * @param string $type
     *
     * @return ConnectionFactoryInterface
     * @throws Exception\InvalidArgumentException
     * @throws Exception\RuntimeException
     */
    public function createFactory($type)
    {
        $map = $this->getFactoryMap();
        if (!array_key_exists($type, $map)) {
            throw new Exception\InvalidArgumentException(sprintf('Factory type "%s" is not in the map', $type));
        }

        $className = $map[$type];
        $factory = new $className;
        if (!$factory instanceof ConnectionFactoryInterface) {
            throw new Exception\RuntimeException(
                sprintf('Factory for type "%s" must be an instance of ConnectionFactoryInterface', $type)
            );
        }

        return $factory;
    }

    /**
     * @return array
     */
    public function getFactoryMap()
    {
        return $this->factoryMap;
    }

    /**
     * @param array $factoryMap
     * @return $this
     */
    public function setFactoryMap(array $factoryMap)
    {
        $this->factoryMap = $factoryMap;
        return $this;
    }
}
