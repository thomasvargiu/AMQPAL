<?php

namespace AMQPAL\Options;

use AMQPAL\AbstractOptions;
use AMQPAL\Exception;

/**
 * Class ExchangeOptions
 *
 * @package AMQPAL\Options
 */
class ExchangeOptions extends AbstractOptions
{
    /**
     * @var string
     */
    protected $name;
    /**
     * @var string
     */
    protected $type;
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
    protected $internal = false;
    /**
     * @todo: probably useless
     * @var bool
     */
    protected $noWait = false;
    /**
     * @var array
     */
    protected $arguments = [];

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
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @param string $type
     *
     * @return $this
     */
    public function setType($type)
    {
        $this->type = $type;

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
    public function isInternal()
    {
        return $this->internal;
    }

    /**
     * @param bool $internal
     *
     * @return $this
     */
    public function setInternal($internal)
    {
        $this->internal = $internal;

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
}
