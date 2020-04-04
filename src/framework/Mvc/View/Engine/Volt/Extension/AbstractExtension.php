<?php
/**
 * This file is part of bops
 *
 * @copyright Copyright (C) 2020 Jayson Wang
 * @license   MIT License
 * @link      https://github.com/wjiec/php-bops
 */
namespace Bops\Mvc\View\Engine\Volt\Extension;

use Phalcon\Mvc\View\Engine\Volt\Compiler;


/**
 * Class AbstractExtension
 *
 * @package Bops\Mvc\View\Engine\Volt\Extension
 */
abstract class AbstractExtension implements ExtensionInterface {

    /**
     * Volt Compiler
     *
     * @var Compiler
     */
    protected $compiler;

    /**
     * Inject extension into compiler
     *
     * @param Compiler $compiler
     * @return ExtensionInterface
     */
    public function inject(Compiler $compiler): ExtensionInterface {
        $this->compiler = $compiler;
        return $this;
    }

}
