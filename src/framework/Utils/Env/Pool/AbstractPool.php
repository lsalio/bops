<?php
/**
 * This file is part of bops
 *
 * @copyright Copyright (C) 2020 Jayson Wang
 * @license   MIT License
 * @link      https://github.com/wjiec/php-bops
 */
namespace Bops\Utils\Env\Pool;


use function Xet\str_has_prefix;

/**
 * Class AbstractPool
 *
 * @package Bops\Utils\Env\Pool
 */
abstract class AbstractPool implements PoolInterface {

    /**
     * @var mixed[]
     */
    protected $pool = [];

    /**
     * Get item from pool
     *
     * @param string $id
     * @param null $default
     * @return mixed
     */
    public function get(string $id, $default = null) {
        return $this->pool[$id] ?? $default;
    }

    /**
     * Add item into pool
     *
     * @param string $id
     * @param $value
     */
    protected function add(string $id, $value) {
        $this->pool[$id] = $value;
    }

    /**
     * Mapping all key-value pair from env
     *
     * @param string $prefix
     * @return array
     */
    static protected function map(string $prefix): array {
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
