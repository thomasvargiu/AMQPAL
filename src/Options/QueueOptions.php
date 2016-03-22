<?php

namespace AMQPAL\Options;

use AMQPAL\AbstractOptions;

/**
 * Class QueueOptions
 *
 * @package AMQPAL\Options
 */
class QueueOptions extends AbstractOptions
{
    /**
     * @var string
     */
    protected $name;
    /**
     * @var bool
     */
    protected $passive = false;
    /**
     * @var bool
     */
    protected $durable = true;
    /**
     * @var bool
     */
    protected $autoDelete = false;
    /**
     * @var bool
     */
    protected $exclusive = false;
    /**
     * @var bool
     */
    protected $noWait = false;
    /**
     * @var array
     */
    protected $arguments = [];
    /**
     * @var array
     */
    protected $routingKeys = [];

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string $name
     *
     * @return $this
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return bool
     */
    public function isPassive()
    {
        return $this->passive;
    }

    /**
     * @param bool $passive
     *
     * @return $this
     */
    public function setPassive($passive)
    {
        $this->passive = $passive;

        return $this;
    }

    /**
     * @return bool
     */
    public function isDurable()
    {
        return $this->durable;
    }

    /**
     * @param bool $durable
     *
     * @return $this
     */
    public function setDurable($durable)
    {
        $this->durable = $durable;

        return $this;
    }

    /**
     * @return bool
     */
    public function isAutoDelete()
    {
        return $this->autoDelete;
    }

    /**
     * @param bool $autoDelete
     *
     * @return $this
     */
    public function setAutoDelete($autoDelete)
    {
        $this->autoDelete = $autoDelete;

        return $this;
    }

    /**
     * @return bool
     */
    public function isExclusive()
    {
        return $this->exclusive;
    }

    /**
     * @param bool $exclusive
     *
     * @return $this
     */
    public function setExclusive($exclusive)
    {
        $this->exclusive = $exclusive;

        return $this;
    }

    /**
     * @return bool
     */
    public function isNoWait()
    {
        return $this->noWait;
    }

    /**
     * @param bool $noWait
     *
     * @return $this
     */
    public function setNoWait($noWait)
    {
        $this->noWait = $noWait;

        return $this;
    }

    /**
     * @return array
     */
    public function getArguments()
    {
        return $this->arguments;
    }

    /**
     * @param array $arguments
     *
     * @return $this
     */
    public function setArguments(array $arguments)
    {
        $this->arguments = $arguments;

        return $this;
    }

    /**
     * @return array
     */
    public function getRoutingKeys()
    {
        return $this->routingKeys;
    }

    /**
     * @param array $routingKeys
     *
     * @return $this
     */
    public function setRoutingKeys(array $routingKeys)
    {
        $this->routingKeys = $routingKeys;

        return $this;
    }
}
