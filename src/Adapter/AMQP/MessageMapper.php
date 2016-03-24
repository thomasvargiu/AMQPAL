<?php

namespace AMQPAL\Adapter\AMQP;

use AMQPEnvelope;
use AMQPAL\Adapter\Message;
use DateTime;

class MessageMapper
{
    /**
     * @var Message
     */
    protected $messagePrototype;

    /**
     * @return Message
     */
    public function getMessagePrototype()
    {
        if (!$this->messagePrototype) {
            $this->messagePrototype = new Message();
        }
        return $this->messagePrototype;
    }

    /**
     * @param Message $messagePrototype
     * @return $this
     */
    public function setMessagePrototype(Message $messagePrototype)
    {
        $this->messagePrototype = $messagePrototype;
        return $this;
    }

    /**
     * @param AMQPEnvelope $libMessage
     * @return Message
     */
    public function toMessage(AMQPEnvelope $libMessage)
    {
        $message = clone $this->getMessagePrototype();
        $message->setBody($libMessage->getBody());
        $message->setRoutingKey($libMessage->getRoutingKey());
        $message->setDeliveryTag($libMessage->getDeliveryTag());
        $message->setDeliveryMode($libMessage->getDeliveryMode());
        $message->setExchangeName($libMessage->getExchangeName());
        $message->setRedelivered($libMessage->isRedelivery());
        $message->setContentType($libMessage->getContentType());
        $message->setContentEncoding($libMessage->getContentEncoding());
        $message->setType($libMessage->getType());
        $message->setDateTime(
            (int)$libMessage->getTimestamp() ? (new \DateTime())->setTimestamp($libMessage->getTimestamp()) : null
        );
        $message->setPriority($libMessage->getPriority());
        $expiration = $libMessage->getExpiration();
        $message->setExpiration(!empty($expiration) ? new \DateTime($libMessage->getExpiration()) : null);
        $message->setUserId($libMessage->getUserId());
        $message->setAppId($libMessage->getAppId());
        $message->setMessageId($libMessage->getMessageId());
        $message->setReplyTo($libMessage->getReplyTo());
        $message->setCorrelationId($libMessage->getCorrelationId());
        $message->setHeaders($libMessage->getHeaders());

        return $message;
    }
}
