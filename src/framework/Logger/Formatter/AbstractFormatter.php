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
 * Class AbstractFormatter
 *
 * @package Bops\Logger\Formatter
 */
abstract class AbstractFormatter implements FormatterInterface {

    /**
     * Interpolates context values into the message placeholders
     *
     * @param string $message
     * @param array $context
     * @return string
     */
    protected static function interpolate(string $message, array $context = []): string {
        $replaces = [];
        foreach ($context as $key => $value) {
            if (!is_array($value) && (!is_object($value) || method_exists($value, '__toString'))) {
                $replaces["{{$key}}"] = $value;
            }
        }

        return strtr($message, $replaces);
    }

}
