<?php
/**
 * This file is part of bops
 *
 * @copyright Copyright (C) 2020 Jayson Wang
 * @license   MIT License
 * @link      https://github.com/wjiec/php-bops
 */
namespace Bops\Mvc;

use Phalcon\Config;
use Phalcon\Mvc\Controller as MvcController;


/**
 * Class Controller
 *
 * @package Bops\Mvc
 * @property Config $config
 * @property Config $moduleConfig
 */
abstract class Controller extends MvcController {

    /**
     * placeholder method
     */
    public function initialize() {}

}
