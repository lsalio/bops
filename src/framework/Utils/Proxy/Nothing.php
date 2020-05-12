<?php
/**
 * This file is part of bops
 *
 * @copyright Copyright (C) 2020 Jayson Wang
 * @license   MIT License
 * @link      https://github.com/wjiec/php-bops
 */
namespace Bops\Utils\Proxy;

class Nothing {

    /**
     * do nothing in this class
     *
     * @param $name
     * @param $arguments
     */
    public function __call($name, $arguments) {}

}
