<?php

namespace SmartOver\MicroService\RabbitMQ\Messages;

use PhpAmqpLib\Message\AMQPMessage;
use SmartOver\MicroService\RabbitMQ\MessageSender;

/**
 * Class Event
 *
 * @package SmartOver\MicroService\RabbitMQ\Messages
 */
class Event implements MessageInterface
{
    /**
     * @var string
     */
    private $channel = 'events';

    /**
     * @var string
     */
    private $event;

    /**
     * @var string
     */
    private $userId;

    /**
     * @var string
     */
    private $ip;

    /**
     * @var string
     */
    private $route;

    /**
     * @var array
     */
    private $data;

    /**
     * Event constructor.
     *
     * @param $event
     * @param $userId
     * @param $ip
     * @param $route
     * @param $data
     */
    public function __construct($event, $userId, $ip, $route, $data)
    {
        $this->event = $event;
        $this->userId = $userId;
        $this->ip = $ip;
        $this->route = $route;
        $this->data = $data;
    }

    /**
     * @return string
     */
    public function getMessage(): string
    {
        return json_encode([
            'event' => $this->event,
            'userId' => $this->userId,
            'ip' => $this->ip,
            'route' => $this->route,
            'data' => $this->data,
        ]);
    }

    /**
     * @param \SmartOver\MicroService\RabbitMQ\MessageSender $sender
     * @return \SmartOver\MicroService\RabbitMQ\MessageSender
     */
    public function publish(MessageSender $sender): MessageSender
    {

        $sender->channel->queue_declare($this->channel, false, true, false, false);

        $msg = new AMQPMessage($this->getMessage(), ['delivery_mode' => AMQPMessage::DELIVERY_MODE_PERSISTENT]);
        $sender->channel->basic_publish($msg, '', $this->channel);

        return $sender;
    }
}