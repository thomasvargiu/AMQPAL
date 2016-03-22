<?php

namespace AMQPAL\Adapter\PhpAmqpLib\Options;

use AMQPAL\AbstractOptions;
use AMQPAL\Adapter\PhpAmqpLib\Factory;

/**
 * Class ConnectionOptions
 *
 * @package AMQPAL\Adapter\PhpAmqpLib\Options
 */
class ConnectionOptions extends AbstractOptions
{
    /**
     * @var string
     */
    protected $type = 'lazy';
    /**
     * @var string
     */
    protected $host = 'localhost';
    /**
     * @var string
     */
    protected $port = 5672;
    /**
     * @var string
     */
    protected $username;
    /**
     * @var string
     */
    protected $password;
    /**
     * @var string
     */
    protected $vhost = '/';
    /**
     * @var bool
     */
    protected $insist = false;
    /**
     * @var string
     */
    protected $loginMethod = 'AMQPLAIN';
    /**
     * @var string
     */
    protected $locale = 'en_US';
    /**
     * @var int
     */
    protected $readWriteTimeout = 0;
    /**
     * @var bool
     */
    protected $keepAlive = false;
    /**
     * @var int
     */
    protected $connectionTimeout = 3;
    /**
     * @var int
     */
    protected $heartbeat = 0;
    /**
     * @var array
     */
    protected $sslOptions = [];
    /**
     * @var Factory\ConnectionFactoryFactory
     */
    protected $connectionFactoryFactory;

    /**
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @param string $type
     * @return $this
     */
    public function setType($type)
    {
        $this->type = $type;
        return $this;
    }

    /**
     * @return string
     */
    public function getHost()
    {
        return $this->host;
    }

    /**
     * @param string $host
     * @return $this
     */
    public function setHost($host)
    {
        $this->host = $host;
        return $this;
    }

    /**
     * @return string
     */
    public function getPort()
    {
        return $this->port;
    }

    /**
     * @param string $port
     * @return $this
     */
    public function setPort($port)
    {
        $this->port = $port;
        return $this;
    }

    /**
     * @return string
     */
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * @param string $username
     * @return $this
     */
    public function setUsername($username)
    {
        $this->username = $username;
        return $this;
    }

    /**
     * @return string
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * @param string $password
     * @return $this
     */
    public function setPassword($password)
    {
        $this->password = $password;
        return $this;
    }

    /**
     * @return string
     */
    public function getVhost()
    {
        return $this->vhost;
    }

    /**
     * @param string $vhost
     * @return $this
     */
    public function setVhost($vhost)
    {
        $this->vhost = $vhost;
        return $this;
    }

    /**
     * @return boolean
     */
    public function isInsist()
    {
        return $this->insist;
    }

    /**
     * @param boolean $insist
     * @return $this
     */
    public function setInsist($insist)
    {
        $this->insist = $insist;
        return $this;
    }

    /**
     * @return string
     */
    public function getLoginMethod()
    {
        return $this->loginMethod;
    }

    /**
     * @param string $loginMethod
     * @return $this
     */
    public function setLoginMethod($loginMethod)
    {
        $this->loginMethod = $loginMethod;
        return $this;
    }

    /**
     * @return string
     */
    public function getLocale()
    {
        return $this->locale;
    }

    /**
     * @param string $locale
     * @return $this
     */
    public function setLocale($locale)
    {
        $this->locale = $locale;
        return $this;
    }

    /**
     * @return int
     */
    public function getReadWriteTimeout()
    {
        return $this->readWriteTimeout;
    }

    /**
     * @param int $readWriteTimeout
     * @return $this
     */
    public function setReadWriteTimeout($readWriteTimeout)
    {
        $this->readWriteTimeout = $readWriteTimeout;
        return $this;
    }

    /**
     * @return boolean
     */
    public function isKeepAlive()
    {
        return $this->keepAlive;
    }

    /**
     * @param boolean $keepAlive
     * @return $this
     */
    public function setKeepAlive($keepAlive)
    {
        $this->keepAlive = $keepAlive;
        return $this;
    }

    /**
     * @return int
     */
    public function getConnectionTimeout()
    {
        return $this->connectionTimeout;
    }

    /**
     * @param int $connectionTimeout
     * @return $this
     */
    public function setConnectionTimeout($connectionTimeout)
    {
        $this->connectionTimeout = $connectionTimeout;
        return $this;
    }

    /**
     * @return int
     */
    public function getHeartbeat()
    {
        return $this->heartbeat;
    }

    /**
     * @param int $heartbeat
     * @return $this
     */
    public function setHeartbeat($heartbeat)
    {
        $this->heartbeat = $heartbeat;
        return $this;
    }

    /**
     * @return array
     */
    public function getSslOptions()
    {
        return $this->sslOptions;
    }

    /**
     * @param array $sslOptions
     * @return $this
     */
    public function setSslOptions(array $sslOptions)
    {
        $this->sslOptions = $sslOptions;
        return $this;
    }

    /**
     * @return Factory\ConnectionFactoryFactory
     */
    public function getConnectionFactoryFactory()
    {
        if (!$this->connectionFactoryFactory) {
            $this->connectionFactoryFactory = new Factory\ConnectionFactoryFactory();
        }
        return $this->connectionFactoryFactory;
    }

    /**
     * @param Factory\ConnectionFactoryFactory $connectionFactoryFactory
     * @return $this
     */
    public function setConnectionFactoryFactory(Factory\ConnectionFactoryFactory $connectionFactoryFactory)
    {
        $this->connectionFactoryFactory = $connectionFactoryFactory;
        return $this;
    }
}
