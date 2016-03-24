<?php

namespace AMQPAL\FunctionalTest\Adapter\AMQP;

use AMQPAL\Adapter\AMQP as Adapter;
use AMQPAL\FunctionalTest\Adapter\AbstractAdapterTestSuite;

class AdapterAdapterTest extends AbstractAdapterTestSuite
{

    public function setUp()
    {
        parent::setUp();

        $this->adapter = new Adapter\AMQP($this->connectionOptions);
        $this->adapter->getConnection()->connect();
    }
}
