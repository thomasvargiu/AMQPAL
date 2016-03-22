<?php

namespace AMQPAL\Adapter\PhpAmqpLib;

use PhpAmqpLib\Message\AMQPMessage;
use AMQPAL\Adapter\ChannelInterface;
use AMQPAL\Adapter\ConnectionInterface;
use AMQPAL\Adapter\ConsumerInterface;
use AMQPAL\Adapter\Exception;
use AMQPAL\Adapter\Message;
use AMQPAL\Adapter\QueueInterface;
use AMQPAL\Options;

/**
 * Class Queue
 *
 * @package AMQPAL\Adapter\PhpAmqpLib
 */
class Queue implements QueueInterface
{
    /**
     * @var Channel
     */
    protected $channel;
    /**
     * @var Options\QueueOptions
     */
    protected $options;
    /**
     * @var MessageMapper
     */
    protected $messageMapper;

    /**
     * Declare a new queue on the broker.
     *
     * @return integer the message count.
     */
    public function declareQueue()
    {
        if (!$this->options->isDeclare()) {
            return $this;
        }

        $this->channel->getResource()->queue_declare(
            $this->options->getName(),
            $this->options->isPassive(),
            $this->options->isDurable(),
            $this->options->isExclusive(),
            $this->options->isAutoDelete(),
            $this->options->isNoWait(),
            $this->options->getArguments()
        );

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
     * @return boolean
     */
    public function bind($exchangeName, $routingKey = null, $noWait = false, array $arguments = [])
    {
        if (null === $routingKey) {
            $routingKey = '';
        }
        $queueName = $this->options->getName();
        $this->channel->getResource()->queue_bind($queueName, $exchangeName, $routingKey, $noWait, $arguments);

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
     */
    public function unbind($exchangeName, $routingKey = null, array $arguments = [])
    {
        if (null === $routingKey) {
            $routingKey = '';
        }
        $queueName = $this->options->getName();
        $this->channel->getResource()->queue_unbind($queueName, $exchangeName, $routingKey, $arguments);

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
     */
    public function ack($deliveryTag, $multiple = false)
    {
        $this->channel->getResource()->basic_ack($deliveryTag, $multiple);

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
     */
    public function nack($deliveryTag, $requeue = false, $multiple = false)
    {
        $this->channel->getResource()->basic_nack($deliveryTag, $multiple, $requeue);

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
     */
    public function reject($deliveryTag, $requeue = false)
    {
        $this->channel->getResource()->basic_reject($deliveryTag, $requeue);

        return $this;
    }

    /**
     * Purge the contents of a queue.
     *
     * @return $this
     */
    public function purge()
    {
        $this->channel->getResource()->queue_purge($this->options->getName());

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
     */
    public function cancel($consumerTag = '')
    {
        $this->channel->getResource()->basic_cancel($consumerTag);

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
     * @throws Exception\InvalidArgumentException
     */
    public function delete($ifUnused = false, $ifEmpty = false, $noWait = false)
    {
        $this->channel->getResource()->queue_delete($this->options->getName(), $ifUnused, $ifEmpty, $noWait);

        return $this;
    }

    /**
     * Retrieve the next message from the queue.
     *
     * @param bool $autoAck
     * @return null|Message
     * @throws \OutOfBoundsException
     */
    public function get($autoAck = false)
    {
        /** @var AMQPMessage $message */
        $message = $this->channel->getResource()->basic_get($this->getOptions()->getName(), !$autoAck);
        if (!$message) {
            return null;
        }

        return $this->getMessageMapper()->toMessage($message);
    }

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
        return $this;
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
     */
    public function consume(
        $consumerTag = null,
        $noLocal = false,
        $autoAck = false,
        $exclusive = false,
        $nowait = false,
        callable $callback = null
    ) {
        if (null === $consumerTag) {
            $consumerTag = '';
        }

        $queue = $this->getOptions()->getName();

        $consumerCallback = null;
        if ($callback) {
            $consumerCallback = new ConsumerCallback($callback, $this);
            $consumerCallback->setMessageMapper($this->getMessageMapper());
        }

        $this->channel->getResource()
            ->basic_consume($queue, $consumerTag, $noLocal, !$autoAck, $exclusive, $nowait, $consumerCallback);

        return $this;
    }

    /**
     * Get the Channel object in use
     *
     * @return ChannelInterface
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
     * @return ConnectionInterface
     */
    public function getConnection()
    {
        return $this->channel->getConnection();
    }
}
