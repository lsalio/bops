<?php
/**
 * This file is part of bops
 *
 * @copyright Copyright (C) 2020 Jayson Wang
 * @license   MIT License
 * @link      https://github.com/wjiec/php-bops
 */
namespace Bops\Utils\Env\Config;

use function Xet\str_has_prefix;


/**
 * Class Loader
 *
 * @package Bops\Utils\Env\Config
 */
class Loader {

    /**
     * Load configure from env and combine
     *
     * @param string $prefix
     * @return array|false
     */
    public static function load(string $prefix) {
        $keys = array_filter(array_keys($_SERVER), function(string $key) use ($prefix) {
            return str_has_prefix($key, $prefix);
        });

        $values = array_map(function(string $key) { return $_SERVER[$key]; }, $keys);
        $keys = array_map(function(string $key) use ($prefix) {
            return strtolower(substr($key, strlen($prefix)));
        }, $keys);

        return array_combine($keys, $values);
    }

}
