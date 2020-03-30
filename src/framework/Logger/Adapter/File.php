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
use Psr\Log\InvalidArgumentException;


/**
 * Class File
 *
 * @package Bops\Logger\Adapter
 */
class File extends AbstractLogger {

    /**
     * Logs with an arbitrary level.
     *
     * @param mixed $level
     * @param string $message
     * @param mixed[] $context
     *
     * @return void
     * @throws InvalidArgumentException
     */
    public function log($level, $message, array $context = []) {
    }

}
