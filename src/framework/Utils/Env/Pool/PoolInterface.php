<?php
/**
 * This file is part of bops
 *
 * @copyright Copyright (C) 2020 Jayson Wang
 * @license   MIT License
 * @link      https://github.com/wjiec/php-bops
 */
namespace Bops\Utils\Env\Pool;


/**
 * Interface PoolInterface
 *
 * @package Bops\Utils\Env\Pool
 */
interface PoolInterface {

    /**
     * Get item from pool
     *
     * @param string $id
     * @param null $default
     * @return mixed
     */
    public function get(string $id, $default = null);

}
