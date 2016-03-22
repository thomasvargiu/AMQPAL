<?php

namespace AMQPAL\Adapter\AMQP;

use AMQPQueue;
use AMQPAL\Adapter\ConsumerInterface;
use AMQPAL\Adapter\Message;
use AMQPAL\Adapter\QueueInterface;
use AMQPAL\Options;

/**
 * Class Queue
 *
 * @package AMQPAL\Adapter\AMQP
 */
class Queue implements QueueInterface
{
    /**
     * @var Channel
     */
    protected $channel;

    /**
     * @var AMQPQueue
     */
    protected $resource;

    /**
     * @var Options\QueueOptions
     */
    protected $options;

    /**
     * @var MessageMapper
     */
    protected $messageMapper;

    /**
     * @return Options\QueueOptions
     */
    public function getOptions()
    {
        return $this->options;
    }

    /**
     * @param Options\QueueOptions $options
     * @return $this
     */
    public function setOptions(Options\QueueOptions $options)
    {
        $this->options = $options;
        $this->configureQueue();
        return $this;
    }

    /**
     * @return $this
     */
    protected function configureQueue()
    {
        $options = $this->getOptions();
        $queue = $this->getResource();

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
        if ($options->isExclusive()) {
            $flags |= AMQP_EXCLUSIVE;
        }
        if ($options->isNoWait()) {
            $flags |= AMQP_NOWAIT;
        }

        $queue->setName($options->getName());
        $queue->setFlags($flags);
        $queue->setArguments($options->getArguments());

        return $this;
    }

    /**
     * @return AMQPQueue
     */
    public function getResource()
    {
        return $this->resource;
    }

    /**
     * @param AMQPQueue $resource
     * @return $this
     */
    public function setResource(AMQPQueue $resource)
    {
        $this->resource = $resource;
        return $this;
    }

    /**
     * Declare a new queue on the broker.
     *
     * @return $this
     * @throws \AMQPChannelException
     * @throws \AMQPConnectionException
     */
    public function declareQueue()
    {
        if (!$this->options->isDeclare()) {
            return $this;
        }

        $this->getResource()->declareQueue();

        return $this;
    }

    /**
     * Bind the given queue to a routing key on an exchange.
     *
     * @param string $exchangeName Name of the exchange to bind to.
     * @param string $routingKey   Pattern or routing key to bind with.
     * @param bool   $noWait       No wait for a reply
     * @param array  $arguments    Additional binding arguments.
     *
     * @return $this
     * @throws \AMQPChannelException
     * @throws \AMQPConnectionException
     */
    public function bind($exchangeName, $routingKey = null, $noWait = false, array $arguments = [])
    {
        $this->getResource()->bind($exchangeName, $routingKey, $arguments);

        return $this;
    }

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
     * @throws \AMQPChannelException
     * @throws \AMQPConnectionException
     */
    public function unbind($exchangeName, $routingKey = null, array $arguments = [])
    {
        $this->getResource()->unbind($exchangeName, $routingKey, $arguments);

        return $this;
    }

    /**
     * Acknowledge the receipt of a message.
     *
     * @param string $deliveryTag   The message delivery tag of which to
     *                              acknowledge receipt.
     * @param bool   $multiple      Acknowledge all previous
     *                              unacked messages as well.
     *
     * @return $this
     * @throws \AMQPChannelException
     * @throws \AMQPConnectionException
     */
    public function ack($deliveryTag, $multiple = false)
    {
        $flags = AMQP_NOPARAM;
        if ($multiple) {
            $flags |= AMQP_MULTIPLE;
        }
        $this->getResource()->ack($deliveryTag, $flags);

        return $this;
    }

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
     * @throws \AMQPChannelException
     * @throws \AMQPConnectionException
     */
    public function nack($deliveryTag, $requeue = false, $multiple = false)
    {
        $flags = AMQP_NOPARAM;
        if ($requeue) {
            $flags |= AMQP_REQUEUE;
        }
        if ($multiple) {
            $flags |= AMQP_MULTIPLE;
        }
        $this->getResource()->nack($deliveryTag, $flags);

        return $this;
    }

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
     * @throws \AMQPChannelException
     * @throws \AMQPConnectionException
     */
    public function reject($deliveryTag, $requeue = false)
    {
        $flags = AMQP_NOPARAM;
        if ($requeue) {
            $flags |= AMQP_REQUEUE;
        }
        $this->getResource()->reject($deliveryTag, $flags);

        return $this;
    }

    /**
     * Purge the contents of a queue.
     *
     * @return $this
     * @throws \AMQPChannelException
     * @throws \AMQPConnectionException
     */
    public function purge()
    {
        $this->getResource()->purge();

        return $this;
    }

    /**
     * Cancel a queue that is already bound to an exchange and routing key.
     *
     * @param string $consumerTag  The queue name to cancel, if the queue
     *                             object is not already representative of
     *                             a queue.
     *
     * @return $this
     * @throws \AMQPChannelException
     * @throws \AMQPConnectionException
     */
    public function cancel($consumerTag = '')
    {
        if (null === $consumerTag) {
            $consumerTag = '';
        }
        $this->getResource()->cancel($consumerTag);

        return $this;
    }

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
     * @throws \AMQPChannelException
     * @throws \AMQPConnectionException
     */
    public function delete($ifUnused = false, $ifEmpty = false, $noWait = false)
    {
        $flags = AMQP_NOPARAM;
        if ($ifUnused) {
            $flags |= AMQP_IFUNUSED;
        }
        if ($ifEmpty) {
            $flags |= AMQP_IFEMPTY;
        }
        if ($noWait) {
            $flags |= AMQP_NOWAIT;
        }
        $this->getResource()->delete($flags);

        return $this;
    }

    /**
     * Retrieve the next message from the queue.
     *
     * @param bool $autoAck
     * @return null|Message
     * @throws \AMQPChannelException
     * @throws \AMQPConnectionException
     */
    public function get($autoAck = false)
    {
        $message = $this->getResource()->get($autoAck ? AMQP_AUTOACK : AMQP_NOPARAM);
        if (!$message) {
            return null;
        }

        return $this->getMessageMapper()->toMessage($message);
    }

    /**
     * @return MessageMapper
     */
    public function getMessageMapper()
    {
        if (!$this->messageMapper) {
            $this->messageMapper = new MessageMapper();
        }
        return $this->messageMapper;
    }

    /**
     * @param MessageMapper $messageMapper
     * @return $this
     */
    public function setMessageMapper(MessageMapper $messageMapper)
    {
        $this->messageMapper = $messageMapper;
        return $this;
    }

    /**
     * Consume messages from a queue.
     *
     * @param string                          $consumerTag  A string describing this consumer. Used
     *                                                      for canceling subscriptions with cancel().
     * @param bool                            $noLocal
     * @param bool                            $autoAck
     * @param bool                            $exclusive
     * @param bool                            $nowait       No wait for a reply.
     * @param callback|ConsumerInterface|null $callback     A callback function to which the
     *                                                      consumed message will be passed.
     * @return $this
     * @throws \AMQPChannelException
     * @throws \AMQPConnectionException
     */
    public function consume(
        $consumerTag = null,
        $noLocal = false,
        $autoAck = false,
        $exclusive = false,
        $nowait = false,
        callable $callback = null
    ) {
        $consumerCallback = null;
        if ($callback) {
            $consumerCallback = new ConsumerCallback($callback, $this);
            $consumerCallback->setMessageMapper($this->getMessageMapper());
        }

        $flags = AMQP_NOPARAM;
        if ($noLocal) {
            $flags |= AMQP_NOLOCAL;
        }
        if ($autoAck) {
            $flags |= AMQP_AUTOACK;
        }
        if ($exclusive) {
            $flags |= AMQP_EXCLUSIVE;
        }
        if ($nowait) {
            $flags |= AMQP_NOWAIT;
        }

        $this->getResource()->consume($consumerCallback, $flags, $consumerTag);

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
     * @param Channel $channel
     * @return $this
     */
    public function setChannel(Channel $channel)
    {
        $this->channel = $channel;
        return $this;
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
