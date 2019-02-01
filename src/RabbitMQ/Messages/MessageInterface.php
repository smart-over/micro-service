<?php

namespace SmartOver\MicroService\RabbitMQ\Messages;

use SmartOver\MicroService\RabbitMQ\MessageSender;

/**
 * Interface MessageInterface
 *
 * @package SmartOver\MicroService\RabbitMQ\Messages
 */
interface MessageInterface
{
    /**
     * @return string
     */
    public function getMessage(): string;

    public function publish(MessageSender $sender): MessageSender;
}