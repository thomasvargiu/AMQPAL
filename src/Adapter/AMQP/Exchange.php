<?php

namespace AMQPAL\Adapter\AMQP;

use AMQPExchange;
use AMQPAL\Adapter\ExchangeInterface;
use AMQPAL\Adapter\Exception;
use AMQPAL\Options;
use AMQPAL\Exception as BaseException;

/**
 * Class Exchange
 *
 * @package AMQPAL\Adapter\AMQP
 */
class Exchange implements ExchangeInterface
{
    /**
     * @var Channel
     */
    protected $channel;
    /**
     * @var AMQPExchange
     */
    protected $resource;
    /**
     * @var Options\ExchangeOptions
     */
    protected $options;

    /**
     * @return Options\ExchangeOptions
     */
    public function getOptions()
    {
        return $this->options;
    }

    /**
     * @param Options\ExchangeOptions|\Traversable|array $exchangeOptions
     * @return $this
     * @throws BaseException\BadMethodCallException
     * @throws BaseException\InvalidArgumentException
     */
    public function setOptions($exchangeOptions)
    {
        if (!$exchangeOptions instanceof Options\ExchangeOptions) {
            $exchangeOptions = new Options\ExchangeOptions($exchangeOptions);
        }
        $this->options = $exchangeOptions;
        $this->configureExchange();
        return $this;
    }

    /**
     * @return AMQPExchange
     */
    public function getResource()
    {
        return $this->resource;
    }

    /**
     * @param AMQPExchange $resource
     * @return $this
     */
    public function setResource(AMQPExchange $resource)
    {
        $this->resource = $resource;
        return $this;
    }

    /**
     * @return $this
     */
    protected function configureExchange()
    {
        $options = $this->getOptions();
        $exchange = $this->getResource();

        $flags = AMQP_NOPARAM;
        if ($options->isDurable()) {
            $flags |= AMQP_DURABLE;
        }
        if ($options->isPassive()) {
            $flags |= AMQP_PASSIVE;
        }
        if ($options->isAutoDelete()) {
            $flags |= AMQP_AUTODELETE;
        }
        if ($options->isInternal()) {
            $flags |= AMQP_INTERNAL;
        }
        if ($options->isNoWait()) {
            $flags |= AMQP_NOWAIT;
        }

        $exchange->setType($options->getType());
        $exchange->setName($options->getName());
        $exchange->setFlags($flags);
        $exchange->setArguments($options->getArguments());

        return $this;
    }

    /**
     * Declare a new exchange on the broker.
     *
     * @return $this
     * @throws Exception\RuntimeException
     * @throws \AMQPExchangeException
     * @throws \AMQPChannelException
     * @throws \AMQPConnectionException
     */
    public function declareExchange()
    {
        $this->resource->declareExchange();

        return $this;
    }

    /**
     * Delete the exchange from the broker.
     *
     * @param bool   $ifUnused      Optional if the exchange should not be
     *                              deleted until no clients are connected to
     *                              it.
     * @param bool   $noWait        No wait for a reply
     *
     * @return $this
     * @throws \AMQPExchangeException
     * @throws \AMQPChannelException
     * @throws \AMQPConnectionException
     */
    public function delete($ifUnused = false, $noWait = false)
    {
        $flags = AMQP_NOPARAM;
        if ($ifUnused) {
            $flags |= AMQP_IFUNUSED;
        }
        if ($noWait) {
            $flags |= AMQP_NOWAIT;
        }

        $this->resource->delete($this->options->getName(), $flags);

        return $this;
    }

    /**
     * Bind to another exchange.
     *
     * Bind an exchange to another exchange using the specified routing key.
     *
     * @param string $exchangeName Name of the exchange to bind.
     * @param string $routingKey   The routing key to use for binding.
     * @param bool   $noWait       No wait for a reply
     * @param array  $arguments    Additional binding arguments.
     *
     * @return $this
     * @throws \AMQPExchangeException
     * @throws \AMQPChannelException
     * @throws \AMQPConnectionException
     */
    public function bind($exchangeName, $routingKey = null, $noWait = false, array $arguments = [])
    {
        $this->resource->bind($exchangeName, $routingKey, $arguments);

        return $this;
    }

    /**
     * Remove binding to another exchange.
     *
     * Remove a routing key binding on an another exchange from the given exchange.
     *
     * @param string $exchangeName Name of the exchange to bind.
     * @param string $routingKey   The routing key to use for binding.
     * @param array  $arguments    Additional binding arguments.
     *
     * @return $this
     * @throws \AMQPExchangeException
     * @throws \AMQPChannelException
     * @throws \AMQPConnectionException
     */
    public function unbind($exchangeName, $routingKey = null, array $arguments = [])
    {
        $this->resource->unbind($exchangeName, $routingKey, $arguments);

        return $this;
    }

    /**
     * Publish a message to an exchange.
     *
     * Publish a message to the exchange represented by the Exchange object.
     *
     * @param string $message      The message to publish.
     * @param string $routingKey   The optional routing key to which to
     *                             publish to.
     * @param bool   $mandatory    Mandatory
     * @param bool   $immediate    Immediate
     * @param array  $attributes   One of content_type, content_encoding,
     *                             message_id, user_id, app_id, delivery_mode,
     *                             priority, timestamp, expiration, type
     *                             or reply_to, headers.
     *
     * @return $this
     * @throws \AMQPExchangeException
     * @throws \AMQPChannelException
     * @throws \AMQPConnectionException
     */
    public function publish(
        $message,
        $routingKey = null,
        $mandatory = false,
        $immediate = false,
        array $attributes = []
    ) {
        $flags = AMQP_NOPARAM;
        if ($mandatory) {
            $flags |= AMQP_MANDATORY;
        }
        if ($immediate) {
            $flags |= AMQP_IMMEDIATE;
        }

        $this->resource->publish($message, $routingKey, $flags, $attributes);

        return $this;
    }

    /**
     * Get the Connection object in use
     *
     * @return Connection
     */
    public function getConnection()
    {
        return $this->getChannel()->getConnection();
    }

    /**
     * @param Channel $channel
     * @return $this
     */
    public function setChannel(Channel $channel)
    {
        $this->channel = $channel;
        return $this;
    }

    /**
     * Get the Channel object in use
     *
     * @return Channel
     */
    public function getChannel()
    {
        return $this->channel;
    }
}
