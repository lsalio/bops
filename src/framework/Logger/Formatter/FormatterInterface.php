<?php
/**
 * This file is part of bops
 *
 * @copyright Copyright (C) 2020 Jayson Wang
 * @license   MIT License
 * @link      https://github.com/wjiec/php-bops
 */
namespace Bops\Logger\Formatter;


/**
 * Interface FormatterInterface
 *
 * @package Bops\Logger\Formatter
 */
interface FormatterInterface {

    /**
     * Format a message
     *
     * @param string $level
     * @param string $message
     * @param array $context = []
     * @return string
     */
    public function format(string $level, string $message, array $context = []): string;

}

