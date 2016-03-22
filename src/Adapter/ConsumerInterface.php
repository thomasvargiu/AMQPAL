<?php

namespace AMQPAL\Adapter;

interface ConsumerInterface
{
    /**
     * @param Message        $message
     * @param QueueInterface $queue
     * @return mixed
     */
    public function __invoke(Message $message, QueueInterface $queue);
}
