<?php

namespace AMQPAL\Adapter;

use AMQPAL\Options;

/**
 * Interface ExchangeInterface
 *
 * @package AMQPAL\Adapter
 */
interface ExchangeInterface
{

    /**
     * Declare a new exchange on the broker.
     *
     * @return $this
     */
    public function declareExchange();

    /**
     * Delete the exchange from the broker.
     *
     * @param bool    $ifUnused     Optional if the exchange should not be
     *                              deleted until no clients are connected to
     *                              it.
     * @param bool   $noWait        No wait for a reply
     *
     * @return $this
     */
    public function delete($ifUnused = false, $noWait = false);

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
    public function bind($exchangeName, $routingKey = null, $noWait = false, array $arguments = []);

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
    public function unbind($exchangeName, $routingKey = null, array $arguments = []);

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
    );

    /**
     * Get the Channel object in use
     *
     * @return ChannelInterface
     */
    public function getChannel();

    /**
     * Get the Connection object in use
     *
     * @return ConnectionInterface
     */
    public function getConnection();
}
