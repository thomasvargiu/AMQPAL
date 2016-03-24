<?php

namespace AMQPAL\Adapter;

class AdapterStub implements AdapterInterface
{
    
    public $options;

    /**
     * PhpAmqpLib constructor.
     *
     * @param array|\Traversable $options
     */
    public function __construct($options)
    {
        $this->options = $options;
    }

    /**
     * @return ConnectionInterface
     */
    public function getConnection()
    {
        
    }

    /**
     * @param resource|null $resource
     * @return ChannelInterface
     */
    public function createChannel($resource = null)
    {
        
    }
}
