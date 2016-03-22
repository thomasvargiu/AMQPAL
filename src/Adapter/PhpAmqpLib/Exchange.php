<?php

namespace AMQPAL\Adapter\PhpAmqpLib;

use PhpAmqpLib\Message\AMQPMessage;
use AMQPAL\Adapter\ExchangeInterface;
use AMQPAL\Options;

/**
 * Class Exchange
 *
 * @package AMQPAL\Adapter\PhpAmqpLib
 */
class Exchange implements ExchangeInterface
{
    /**
     * @var Channel
     */
    protected $channel;
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
     * @param Options\ExchangeOptions $exchangeOptions
     * @return $this
     */
    public function setOptions(Options\ExchangeOptions $exchangeOptions)
    {
        $this->options = $exchangeOptions;
        return $this;
    }

    /**
     * Declare a new exchange on the broker.
     *
     * @return $this
     */
    public function declareExchange()
    {
        $this->declareSingleExchange($this->options);

        return $this;
    }

    /**
     * Declare a new exchange on the broker.
     *
     * @param Options\ExchangeOptions $options
     * @return $this
     */
    protected function declareSingleExchange(Options\ExchangeOptions $options)
    {
        if (!$options->isDeclare()) {
            return $this;
        }

        $this->channel->getResource()->exchange_declare(
            $options->getName(),
            $options->getType(),
            $options->isPassive(),
            $options->isDurable(),
            $options->isAutoDelete(),
            $options->isInternal(),
            $options->isNoWait(),
            $options->getArguments()
        );

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
     */
    public function delete($ifUnused = false, $noWait = false)
    {
        $this->channel->getResource()->exchange_delete($this->options->getName(), $ifUnused, $noWait);

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
     */
    public function bind($exchangeName, $routingKey = null, $noWait = false, array $arguments = [])
    {
        if (null === $routingKey) {
            $routingKey = '';
        }
        $name = $this->options->getName();
        $this->channel->getResource()->exchange_bind($name, $exchangeName, $routingKey, $noWait, $arguments);

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
     */
    public function unbind($exchangeName, $routingKey = null, array $arguments = [])
    {
        if (null === $routingKey) {
            $routingKey = '';
        }
        $name = $this->options->getName();
        $this->channel->getResource()->exchange_unbind($name, $exchangeName, $routingKey, $arguments);

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
     */
    public function publish(
        $message,
        $routingKey = null,
        $mandatory = false,
        $immediate = false,
        array $attributes = []
    ) {
        if (null === $routingKey) {
            $routingKey = '';
        }
        $options = $this->options;

        $AMQPMessage = new AMQPMessage($message, $attributes);

        $this->channel->getResource()->basic_publish(
            $AMQPMessage,
            $options->getName(),
            $routingKey,
            $mandatory,
            $immediate
        );

        return $this;
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

    /**
     * Get the Connection object in use
     *
     * @return Connection
     */
    public function getConnection()
    {
        return $this->channel->getConnection();
    }
}
