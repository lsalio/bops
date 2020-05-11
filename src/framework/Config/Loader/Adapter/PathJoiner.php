<?php
/**
 * This file is part of bops
 *
 * @copyright Copyright (C) 2020 Jayson Wang
 * @license   MIT License
 * @link      https://github.com/wjiec/php-bops
 */
namespace Bops\Config\Loader\Adapter;

use Bops\Config\Loader\AbstractLoader;


/**
 * Class PathJoiner
 *
 * @package Bops\Config\Loader\Adapter
 */
class PathJoiner extends AbstractLoader {

    /**
     * Root directory
     *
     * @var string
     */
    protected $root;

    /**
     * LocalDirectory constructor.
     *
     * @param string $root
     */
    public function __construct(string $root) {
        $this->root = rtrim($root, '\\/');
    }

    /**
     * Returns the path of the configure file
     *
     * @param string $name
     * @return string
     */
    public function pathOf(string $name): string {
        return $this->root . "/{$name}";
    }

}
