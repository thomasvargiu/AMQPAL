<?php

namespace AMQPAL\Adapter;

class AdapterFactory
{
    /**
     * @var array
     */
    protected $adapters = [
        'amqp' => AMQP\AMQP::class,
        'phpamqplib' => PhpAmqpLib\PhpAmqpLib::class,
    ];

    /**
     * @param array $options
     * @return AdapterInterface
     * @throws Exception\InvalidArgumentException
     * @throws Exception\OutOfBoundsException
     */
    public function createAdapter(array $options)
    {
        if (!array_key_exists('name', $options)) {
            throw new Exception\InvalidArgumentException('Unable to find \'name\' key.');
        }

        if (!array_key_exists('options', $options)) {
            throw new Exception\InvalidArgumentException('Unable to find \'options\' key.');
        }
        
        $name = strtolower($options['name']);
        
        if (!array_key_exists($name, $this->adapters)) {
            throw new Exception\OutOfBoundsException(sprintf('Unable to find adapter \'%s\'.', $name));
        }
        
        $className = $this->adapters[$name];
        
        return new $className($options['options']);
    }

    /**
     * @return array
     */
    public function getAdapters()
    {
        return $this->adapters;
    }

    /**
     * @param array $adapters
     * @return $this
     */
    public function setAdapters(array $adapters)
    {
        $this->adapters = $adapters;

        return $this;
    }

    /**
     * @param string $name The adapter name
     * @param string $adapterClass The adapter class
     * @return $this
     */
    public function setAdapter($name, $adapterClass)
    {
        $name = strtolower($name);
        $this->adapters[$name] = $adapterClass;

        return $this;
    }
}
