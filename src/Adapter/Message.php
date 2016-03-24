<?php

namespace AMQPAL\Adapter;

use DateTime;

class Message
{
    /**
     * @var string
     */
    protected $body;
    /**
     * @var string
     */
    protected $routingKey;
    /**
     * @var int
     */
    protected $deliveryTag;
    /**
     * @var int
     */
    protected $deliveryMode;
    /**
     * @var string
     */
    protected $exchangeName;
    /**
     * @var bool
     */
    protected $redelivered = false;
    /**
     * @var string
     */
    protected $contentType;
    /**
     * @var string
     */
    protected $contentEncoding;
    /**
     * @var string
     */
    protected $type;
    /**
     * @var DateTime
     */
    protected $dateTime;
    /**
     * @var int
     */
    protected $priority;
    /**
     * @var DateTime
     */
    protected $expiration;
    /**
     * @var string
     */
    protected $userId;
    /**
     * @var string
     */
    protected $appId;
    /**
     * @var string
     */
    protected $messageId;
    /**
     * @var string
     */
    protected $replyTo;
    /**
     * @var string
     */
    protected $correlationId;
    /**
     * @var array
     */
    protected $headers = [];

    /**
     * @return string
     */
    public function getBody()
    {
        return $this->body;
    }

    /**
     * @return string
     */
    public function getRoutingKey()
    {
        return $this->routingKey;
    }

    /**
     * @return int
     */
    public function getDeliveryTag()
    {
        return $this->deliveryTag;
    }

    /**
     * @return int
     */
    public function getDeliveryMode()
    {
        return $this->deliveryMode;
    }

    /**
     * @return string
     */
    public function getExchangeName()
    {
        return $this->exchangeName;
    }

    /**
     * @return boolean
     */
    public function isRedelivered()
    {
        return $this->redelivered;
    }

    /**
     * @return string
     */
    public function getContentType()
    {
        return $this->contentType;
    }

    /**
     * @return string
     */
    public function getContentEncoding()
    {
        return $this->contentEncoding;
    }

    /**
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @return DateTime
     */
    public function getDateTime()
    {
        return $this->dateTime;
    }

    /**
     * @return int
     */
    public function getPriority()
    {
        return $this->priority;
    }

    /**
     * @return DateTime
     */
    public function getExpiration()
    {
        return $this->expiration;
    }

    /**
     * @return string
     */
    public function getUserId()
    {
        return $this->userId;
    }

    /**
     * @return string
     */
    public function getAppId()
    {
        return $this->appId;
    }

    /**
     * @return string
     */
    public function getMessageId()
    {
        return $this->messageId;
    }

    /**
     * @return string
     */
    public function getReplyTo()
    {
        return $this->replyTo;
    }

    /**
     * @return string
     */
    public function getCorrelationId()
    {
        return $this->correlationId;
    }

    /**
     * @return array
     */
    public function getHeaders()
    {
        return $this->headers;
    }

    /**
     * @param string $body
     * @return $this
     */
    public function setBody($body)
    {
        $this->body = $body;
        return $this;
    }

    /**
     * @param string $routingKey
     * @return $this
     */
    public function setRoutingKey($routingKey)
    {
        $this->routingKey = $routingKey;
        return $this;
    }

    /**
     * @param int $deliveryTag
     * @return $this
     */
    public function setDeliveryTag($deliveryTag)
    {
        $this->deliveryTag = $deliveryTag;
        return $this;
    }

    /**
     * @param int $deliveryMode
     * @return $this
     */
    public function setDeliveryMode($deliveryMode)
    {
        $this->deliveryMode = $deliveryMode;
        return $this;
    }

    /**
     * @param string $exchangeName
     * @return $this
     */
    public function setExchangeName($exchangeName)
    {
        $this->exchangeName = $exchangeName;
        return $this;
    }

    /**
     * @param boolean $redelivered
     * @return $this
     */
    public function setRedelivered($redelivered)
    {
        $this->redelivered = $redelivered;
        return $this;
    }

    /**
     * @param string $contentType
     * @return $this
     */
    public function setContentType($contentType)
    {
        $this->contentType = $contentType;
        return $this;
    }

    /**
     * @param string $contentEncoding
     * @return $this
     */
    public function setContentEncoding($contentEncoding)
    {
        $this->contentEncoding = $contentEncoding;
        return $this;
    }

    /**
     * @param string $type
     * @return $this
     */
    public function setType($type)
    {
        $this->type = $type;
        return $this;
    }

    /**
     * @param DateTime $dateTime
     * @return $this
     */
    public function setDateTime(DateTime $dateTime = null)
    {
        $this->dateTime = $dateTime;
        return $this;
    }

    /**
     * @param int $priority
     * @return $this
     */
    public function setPriority($priority)
    {
        $this->priority = $priority;
        return $this;
    }

    /**
     * @param DateTime $expiration
     * @return $this
     */
    public function setExpiration(DateTime $expiration = null)
    {
        $this->expiration = $expiration;
        return $this;
    }

    /**
     * @param string $userId
     * @return $this
     */
    public function setUserId($userId)
    {
        $this->userId = $userId;
        return $this;
    }

    /**
     * @param string $appId
     * @return $this
     */
    public function setAppId($appId)
    {
        $this->appId = $appId;
        return $this;
    }

    /**
     * @param string $messageId
     * @return $this
     */
    public function setMessageId($messageId)
    {
        $this->messageId = $messageId;
        return $this;
    }

    /**
     * @param string $replyTo
     * @return $this
     */
    public function setReplyTo($replyTo)
    {
        $this->replyTo = $replyTo;
        return $this;
    }

    /**
     * @param string $correlationId
     * @return $this
     */
    public function setCorrelationId($correlationId)
    {
        $this->correlationId = $correlationId;
        return $this;
    }

    /**
     * @param array $headers
     * @return $this
     */
    public function setHeaders(array $headers)
    {
        $this->headers = $headers;
        return $this;
    }
}
