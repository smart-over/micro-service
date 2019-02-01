<?php

namespace SmartOver\MicroService\Test;

use PhpAmqpLib\Connection\AMQPStreamConnection;
use PHPUnit\Framework\TestCase;
use SmartOver\MicroService\RabbitMQ\Messages\Event;
use SmartOver\MicroService\RabbitMQ\MessageSender;

/**
 * Class TestQueue
 *
 * @package SmartOver\MicroService\Test
 */
class TestQueue extends TestCase
{
    /**
     * @throws \Exception
     */
    public function testAddEvent()
    {

        $event = new Event('test_event', rand(99999, 9999999), '192.168.'.rand(1, 255).'.'.rand(1, 255), 'test_screen', [
            'foo' => rand(99999, 9999999),
            'bar' => rand(99999, 9999999),
        ]);

        $connection = new AMQPStreamConnection('localhost', 5672, 'guest', 'guest');

        $send = MessageSender::getInstance()->setConnection($connection)->send($event);

        $this->assertInstanceOf(MessageSender::class, $send);

        $sendAgain = MessageSender::getInstance()->send($event);

        $this->assertInstanceOf(MessageSender::class, $sendAgain);

        MessageSender::getInstance()->close();
    }
}