<?php

namespace AMQPAL\Adapter\AMQP\Options;

use AMQPAL\AbstractOptions;

class ConnectionOptions extends AbstractOptions
{
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
     * @var int
     */
    protected $readTimeout = 0;
    /**
     * @var int
     */
    protected $writeTimeout = 0;
    /**
     * @var int
     */
    protected $connectTimeout = 3;
    /**
     * @var int
     */
    protected $channelMax;
    /**
     * @var int
     */
    protected $frameMax;
    /**
     * @var int
     */
    protected $heartbeat = 0;
    /**
     * @var bool
     */
    protected $persistent = false;

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
     * @return int
     */
    public function getReadTimeout()
    {
        return $this->readTimeout;
    }

    /**
     * @param int $readTimeout
     * @return $this
     */
    public function setReadTimeout($readTimeout)
    {
        $this->readTimeout = $readTimeout;
        return $this;
    }

    /**
     * @return int
     */
    public function getWriteTimeout()
    {
        return $this->writeTimeout;
    }

    /**
     * @param int $writeTimeout
     * @return $this
     */
    public function setWriteTimeout($writeTimeout)
    {
        $this->writeTimeout = $writeTimeout;
        return $this;
    }

    /**
     * @return int
     */
    public function getConnectTimeout()
    {
        return $this->connectTimeout;
    }

    /**
     * @param int $connectTimeout
     * @return $this
     */
    public function setConnectTimeout($connectTimeout)
    {
        $this->connectTimeout = $connectTimeout;
        return $this;
    }

    /**
     * @return int
     */
    public function getChannelMax()
    {
        return $this->channelMax;
    }

    /**
     * @param int $channelMax
     * @return $this
     */
    public function setChannelMax($channelMax)
    {
        $this->channelMax = $channelMax;
        return $this;
    }

    /**
     * @return int
     */
    public function getFrameMax()
    {
        return $this->frameMax;
    }

    /**
     * @param int $frameMax
     * @return $this
     */
    public function setFrameMax($frameMax)
    {
        $this->frameMax = $frameMax;
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
     * @return boolean
     */
    public function isPersistent()
    {
        return $this->persistent;
    }

    /**
     * @param boolean $persistent
     * @return $this
     */
    public function setPersistent($persistent)
    {
        $this->persistent = (bool)$persistent;
        return $this;
    }
}
