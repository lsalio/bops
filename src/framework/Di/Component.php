<?php
/**
 * This file is part of bops
 *
 * @copyright Copyright (C) 2020 Jayson Wang
 * @license   MIT License
 * @link      https://github.com/wjiec/php-bops
 */
namespace Bops\Di;

use Bops\Navigator\NavigatorInterface;
use Phalcon\Di\Injectable;


/**
 * Class Component
 *
 * @package Bops\Di
 * @property NavigatorInterface $navigator
 */
abstract class Component extends Injectable {

    /**
     * Component constructor
     */
    public function __construct() {
        $this->setDI(container());
    }

}
