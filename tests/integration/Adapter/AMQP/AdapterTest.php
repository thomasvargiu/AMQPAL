<?php

namespace AMQPAL\IntegrationTest\Adapter\AMQP;

use AMQPAL\Adapter\AMQP as Adapter;
use AMQPAL\IntegrationTest\Adapter\AbstractAdapterTestSuite;

class AdapterAdapterTest extends AbstractAdapterTestSuite
{

    public function setUp()
    {
        parent::setUp();

        $this->adapter = new Adapter\AMQP($this->connectionOptions);
        $this->adapter->getConnection()->connect();
    }
}
