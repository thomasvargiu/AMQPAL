<?php

namespace AMQPAL\Adapter;

use AMQPAL\Options;

/**
 * Interface QueueInterface
 *
 * @package AMQPAL\Adapter
 */
interface QueueInterface
{

    /**
     * @return Options\QueueOptions
     */
    public function getOptions();

    /**
     * Declare a new queue on the broker.
     *
     * @return $this
     */
    public function declareQueue();

    /**
     * Bind the given queue to a routing key on an exchange.
     *
     * @param string $exchangeName Name of the exchange to bind to.
     * @param string $routingKey   Pattern or routing key to bind with.
     * @param bool   $noWait       No wait for a reply
     * @param array  $arguments    Additional binding arguments.
     *
     * @return $this
     */
    public function bind($exchangeName, $routingKey = null, $noWait = false, array $arguments = []);

    /**
     * Remove a routing key binding on an exchange from the given queue.
     *
     * @param string $exchangeName  The name of the exchange on which the
     *                              queue is bound.
     * @param string $routingKey    The binding routing key used by the
     *                              queue.
     * @param array  $arguments     Additional binding arguments.
     *
     * @return $this
     */
    public function unbind($exchangeName, $routingKey = null, array $arguments = []);

    /**
     * Acknowledge the receipt of a message.
     *
     * @param string $deliveryTag   The message delivery tag of which to
     *                              acknowledge receipt.
     * @param bool   $multiple      Acknowledge all previous
     *                              unacked messages as well.
     *
     * @return $this
     */
    public function ack($deliveryTag, $multiple = false);

    /**
     * Mark a message as explicitly not acknowledged.
     *
     * Mark the message identified by delivery_tag as explicitly not
     * acknowledged. This method can only be called on messages that have not
     * yet been acknowledged. When called, the broker will immediately put the
     * message back onto the queue, instead of waiting until the connection is
     * closed. This method is only supported by the RabbitMQ broker. The
     * behavior of calling this method while connected to any other broker is
     * undefined.
     *
     * @param string $deliveryTag   Delivery tag of last message to reject.
     * @param bool   $requeue       Requeue the message(s).
     * @param bool   $multiple      Mark as not acknowledge all previous
     *                              unacked messages as well.
     *
     * @return $this
     */
    public function nack($deliveryTag, $requeue = false, $multiple = false);

    /**
     * Mark one message as explicitly not acknowledged.
     *
     * Mark the message identified by delivery_tag as explicitly not
     * acknowledged. This method can only be called on messages that have not
     * yet been acknowledged.
     *
     * @param string $deliveryTag Delivery tag of the message to reject.
     * @param bool   $requeue     Requeue the message(s).
     *
     * @return $this
     */
    public function reject($deliveryTag, $requeue = false);

    /**
     * Purge the contents of a queue.
     *
     * @return $this
     */
    public function purge();

    /**
     * Cancel a queue that is already bound to an exchange and routing key.
     *
     * @param string $consumerTag  The queue name to cancel, if the queue
     *                             object is not already representative of
     *                             a queue.
     *
     * @return $this
     */
    public function cancel($consumerTag = '');

    /**
     * Delete a queue from the broker.
     *
     * This includes its entire contents of unread or unacknowledged messages.
     *
     * @param bool $ifUnused        Optionally $ifUnused can be specified
     *                              to indicate the queue should not be
     *                              deleted until no clients are connected to
     *                              it.
     * @param bool $ifEmpty         Optionally $ifUnused can be specified
     *                              to indicate the queue should not be
     *                              deleted until it's empty
     * @param bool $noWait          No wait for a reply
     *
     * @return $this
     */
    public function delete($ifUnused = false, $ifEmpty = false, $noWait = false);

    /**
     * Retrieve the next message from the queue.
     *
     * @param bool $autoAck
     * @return null|Message
     */
    public function get($autoAck = false);

    /**
     * Consume messages from a queue (blocking function).
     *
     * @param callback|ConsumerInterface|null $callback     A callback function to which the
     *                                                      consumed message will be passed.
     * @param bool                            $noLocal
     * @param bool                            $autoAck
     * @param bool                            $exclusive
     * @param string                          $consumerTag  A string describing this consumer. Used
     *                                                      for canceling subscriptions with cancel().
     * @return $this
     */
    public function consume(
        callable $callback = null,
        $noLocal = false,
        $autoAck = false,
        $exclusive = false,
        $consumerTag = null
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
