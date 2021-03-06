<?php

namespace AMQPAL\Adapter;

use AMQPAL\Options;

interface AdapterInterface
{
    /**
     * PhpAmqpLib constructor.
     *
     * @param array|\Traversable $options
     */
    public function __construct($options);
    
    /**
     * @return ConnectionInterface
     */
    public function getConnection();
}
