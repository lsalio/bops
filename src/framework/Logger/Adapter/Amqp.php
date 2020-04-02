<?php
/**
 * This file is part of bops
 *
 * @copyright Copyright (C) 2020 Jayson Wang
 * @license   MIT License
 * @link      https://github.com/wjiec/php-bops
 */
namespace Bops\Logger\Adapter;

use Bops\Logger\AbstractLogger;
use Bops\Logger\Formatter\Adapter\Plain;
use Bops\Logger\Formatter\FormatterInterface;
use Bops\Logger\Utils\Amqp\RoutingKey\Adapter\Obvious;
use Bops\Logger\Utils\Amqp\RoutingKey\GeneratorInterface;
use PhpAmqpLib\Channel\AMQPChannel;
use PhpAmqpLib\Message\AMQPMessage;


/**
 * Class Amqp
 *
 * @package Bops\Logger\Adapter
 */
class Amqp extends AbstractLogger {

    /**
     * Amqp channel
     *
     * @var AMQPChannel
     */
    protected $channel;

    /**
     * Name of the exchange
     *
     * @var string
     */
    protected $exchange;

    /**
     * Generator for routing key
     *
     * @var GeneratorInterface
     */
    protected $generator;

    /**
     * Amqp constructor.
     *
     * @param AMQPChannel $channel
     * @param string|null $exchange
     */
    public function __construct(AMQPChannel $channel, ?string $exchange = null) {
        $this->channel = $channel;
        $this->exchange = $exchange;
        $this->generator = new Obvious();
    }

    /**
     * Sets a generator
     *
     * @param GeneratorInterface $generator
     * @return $this
     */
    public function setGenerator(GeneratorInterface $generator) {
        $this->generator = $generator;
        return $this;
    }

    /**
     * Closes the logger
     *
     * @return bool
     */
    public function close(): bool {
        $this->channel->close();
        return true;
    }

    /**
     * Logs with an arbitrary level really
     *
     * @param string $level
     * @param string $message
     * @param array $context
     * @return mixed|void
     */
    protected function doLog(string $level, string $message, array $context = []) {
        $data = $this->getFormatter()->format($level, $message, $context);
        $routingKey = $this->generator->__invoke($level, $context);

        $this->channel->basic_publish($this->createAmqpMessage($data), $this->exchange, $routingKey);
    }

    /**
     * Returns the formatter by default
     *
     * @return FormatterInterface
     */
    protected function getDefaultFormatter(): FormatterInterface {
        return new Plain();
    }

    /**
     * Create a message for amqp
     *
     * @param string $message
     * @return AMQPMessage
     */
    protected function createAmqpMessage(string $message): AMQPMessage {
        return new AMQPMessage($message, [
            'delivery_mode' => 2
        ]);
    }

}
