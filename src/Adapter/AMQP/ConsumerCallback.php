<?php

namespace AMQPAL\Adapter\AMQP;

use AMQPEnvelope;

class ConsumerCallback
{
    /**
     * @var callable
     */
    protected $callback;
    /**
     * @var Queue
     */
    protected $queue;
    /**
     * @var MessageMapper
     */
    protected $messageMapper;

    /**
     * ConsumerCallback constructor.
     *
     * @param callable $callback
     * @param Queue    $queue
     */
    public function __construct(callable $callback, Queue $queue)
    {
        $this->callback = $callback;
        $this->queue = $queue;
    }

    /**
     * @param AMQPEnvelope $message
     * @return mixed
     * @throws \OutOfBoundsException
     */
    public function __invoke(AMQPEnvelope $message)
    {
        $convertedMessage = $this->getMessageMapper()->toMessage($message);
        return call_user_func($this->callback, $convertedMessage, $this->queue);
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
    public function setMessageMapper($messageMapper)
    {
        $this->messageMapper = $messageMapper;
        return $this;
    }
}
