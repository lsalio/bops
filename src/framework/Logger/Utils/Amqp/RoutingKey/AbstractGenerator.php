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
 * Class AbstractGenerator
 *
 * @package Bops\Logger\Utils\Amqp\RoutingKey
 */
abstract class AbstractGenerator implements GeneratorInterface {

    /**
     * Generate a routing key
     *
     * @param string $level
     * @param array $context
     * @return string
     */
    public function __invoke(string $level, array $context = []): string {
        return strtolower($this->doGenerate($level, $context));
    }

    /**
     * Generate a routing key really
     *
     * @param string $level
     * @param array $context
     * @return string
     */
    abstract protected function doGenerate(string $level, array $context): string;

}
