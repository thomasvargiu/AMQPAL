<?php

namespace AMQPAL\Adapter\PhpAmqpLib;

use PhpAmqpLib\Message\AMQPMessage;
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
     * @param AMQPMessage $libMessage
     * @return Message
     * @throws \OutOfBoundsException
     */
    public function toMessage(AMQPMessage $libMessage)
    {
        $message = clone $this->getMessagePrototype();
        $message->setBody($libMessage->getBody());
        $message->setRoutingKey($libMessage->get($libMessage->has('routing_key') ? 'routing_key' : null));
        $message->setDeliveryTag($libMessage->has('delivery_tag') ? $libMessage->get('delivery_tag') : null);
        $message->setDeliveryMode($libMessage->has('delivery_mode') ? $libMessage->get('delivery_mode') : null);
        $message->setExchangeName($libMessage->has('exchange') ? $libMessage->get('exchange') : null);
        $message->setRedelivered($libMessage->has('redelivered') ? $libMessage->get('redelivered') : false);
        $message->setContentType($libMessage->has('content_type') ? $libMessage->get('content_type') : null);
        $message->setContentEncoding(
            $libMessage->has('content_encoding') ? $libMessage->get('content_encoding') : null
        );
        $message->setType($libMessage->has('type') ? $libMessage->get('type') : null);
        $message->setDateTime((new DateTime())->setTimestamp($libMessage->get('timestamp')));
        $message->setPriority($libMessage->has('priority') ? $libMessage->get('priority') : null);
        $message->setExpiration($libMessage->has('expiration') ? new \DateTime($libMessage->get('expiration')) : null);
        $message->setUserId($libMessage->has('user_id') ? $libMessage->get('user_id') : null);
        $message->setAppId($libMessage->has('app_id') ? $libMessage->get('app_id') : null);
        $message->setMessageId($libMessage->has('message_id') ? $libMessage->get('message_id') : null);
        $message->setReplyTo($libMessage->has('reply_to') ? $libMessage->get('reply_to') : null);
        $message->setCorrelationId($libMessage->has('correlation_id') ? $libMessage->get('correlation_id') : null);
        $message->setHeaders($libMessage->has('application_headers') ? $libMessage->get('application_headers') : []);
        return $message;
    }
}
