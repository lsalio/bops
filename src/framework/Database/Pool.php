<?php
/**
 * This file is part of bops
 *
 * @copyright Copyright (C) 2020 Jayson Wang
 * @license   MIT License
 * @link      https://github.com/wjiec/php-bops
 */
namespace Bops\Database;

use Bops\Utils\Env\Pool\Adapter\Connection;


/**
 * Class Pool
 *
 * @package Bops\Database
 */
class Pool extends Connection {

    /**
     * Returns the prefix string for mapping
     *
     * @param string $name
     * @return string
     */
    protected function getPrefix(string $name): string {
        return sprintf('SERVICE_DATABASE_%s_', strtoupper($name));
    }

    /**
     * Make a connection and append into pool
     *
     * @param array $config
     * @return mixed
     */
    protected function doMakeConnection(array $config) {
        return '';
    }

}
