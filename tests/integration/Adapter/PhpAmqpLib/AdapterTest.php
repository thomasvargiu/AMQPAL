<?php

namespace AMQPAL\IntegrationTest\Adapter\PhpAmqpLib;

use AMQPAL\Adapter\PhpAmqpLib as Adapter;
use AMQPAL\IntegrationTest\Adapter\AbstractAdapterTestSuite;

class AdapterAdapterTest extends AbstractAdapterTestSuite
{

    public function setUp()
    {
        parent::setUp();

        $this->adapter = new Adapter\PhpAmqpLib($this->connectionOptions);
        $this->adapter->getConnection()->connect();
    }
}
