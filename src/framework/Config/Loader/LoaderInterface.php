<?php
/**
 * This file is part of bops
 *
 * @copyright Copyright (C) 2020 Jayson Wang
 * @license   MIT License
 * @link      https://github.com/wjiec/php-bops
 */
namespace Bops\Config\Loader;


/**
 * Interface LoaderInterface
 *
 * @package Bops\Config\Loader
 */
interface LoaderInterface {

    /**
     * Returns the path of the configure file
     *
     * @param string $name
     * @return string
     */
    public function pathOf(string $name): string;

}
