<?php
/**
 * This file is part of bops
 *
 * @copyright Copyright (C) 2020 Jayson Wang
 * @license   MIT License
 * @link      https://github.com/wjiec/php-bops
 */
namespace Bops\Logger\Utils\Amqp\RoutingKey\Adapter;

use Bops\Logger\Utils\Amqp\RoutingKey\AbstractGenerator;
use function Xet\array_at;


/**
 * Class Obvious
 *
 * @package Bops\Logger\Utils\Amqp\RoutingKey\Adapter
 */
class Obvious extends AbstractGenerator {

    /**
     * Generate a routing key really
     *
     * @param string $level
     * @param array $context
     * @return string
     */
    protected function doGenerate(string $level, array $context): string {
        return join('.', array_filter([
            $level,
            array_at($context, 'channel', '')
        ]));
    }

}
