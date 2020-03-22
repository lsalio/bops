<?php
/**
 * This file is part of bops
 *
 * @copyright Copyright (C) 2020 Jayson Wang
 * @license   MIT License
 * @link      https://github.com/wjiec/php-bops
 */
namespace Bops\Mvc\View;

use Phalcon\Mvc\ViewInterface as MvcViewInterface;


/**
 * Interface ViewInterface
 *
 * @package Bops\Mvc\View
 */
interface ViewInterface extends MvcViewInterface {

    /**
     * Load config
     *
     * @param array $config
     * @return $this
     */
    public function loadConfig(array $config);

}
