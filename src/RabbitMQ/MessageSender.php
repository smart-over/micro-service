<?php

namespace SmartOver\MicroService\RabbitMQ;

use Exception;
use PhpAmqpLib\Connection\AMQPStreamConnection;
use SmartOver\MicroService\RabbitMQ\Messages\MessageInterface;

/**
 * Class SendMessage
 *
 * @package SmartOver\MicroService\RabbitMQ
 */
class MessageSender
{
    /**
     * @var null
     */
    private static $instance = null;

    /**
     * @var \PhpAmqpLib\Connection\AMQPStreamConnection
     */
    public $connection;

    /**
     * @var |PhpAmqpLib\Channel\AMQPChannel
     */
    public $channel;

    /**
     * @var array
     */
    public $declaredChannels;

    /**
     * @return \SmartOver\MicroService\RabbitMQ\MessageSender|null
     */
    public static function getInstance()
    {
        if (! self::$instance) {

            self::$instance = new MessageSender();
        }

        return self::$instance;
    }

    /**
     * @param \PhpAmqpLib\Connection\AMQPStreamConnection $connection
     * @return $this
     */
    public function setConnection(AMQPStreamConnection $connection)
    {

        $this->connection = $connection;
        $this->channel = $connection->channel();

        return $this;
    }

    /**
     * @param \SmartOver\MicroService\RabbitMQ\Messages\MessageInterface $message
     * @return \SmartOver\MicroService\RabbitMQ\MessageSender
     * @throws \Exception
     */
    public function send(MessageInterface $message)
    {

        if (! $this->connection) {
            throw new Exception('RabbitMQ connection is not defined');
        }

        return self::$instance = $message->publish($this);
    }

    /**
     * @return void Close channel and connection
     */
    public function close()
    {

        $this->channel->close();
        $this->connection->close();
    }
}