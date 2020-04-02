<?php
/**
 * This file is part of bops
 *
 * @copyright Copyright (C) 2020 Jayson Wang
 * @license   MIT License
 * @link      https://github.com/wjiec/php-bops
 */
namespace Bops\Logger\Utils\Amqp\RoutingKey;


/**
 * Interface GeneratorInterface
 *
 * @package Bops\Logger\Utils\Amqp\RoutingKey
 */
interface GeneratorInterface {

    /**
     * Generate a routing key
     *
     * @param string $level
     * @param array $context
     * @return string
     */
    public function __invoke(string $level, array $context = []): string;

}
