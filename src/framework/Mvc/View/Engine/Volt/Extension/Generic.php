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
 * Class Generic
 *
 * @package Bops\Mvc\View\Engine\Volt\Extension
 */
class Generic {

    /**
     * The view compiler
     *
     * @var Compiler
     */
    protected $compiler;

    /**
     * Functions constructor.
     * @param Compiler $compiler
     */
    public function __construct(Compiler $compiler) {
        $this->compiler = $compiler;
    }

    /**
     * Compile any function call in a template.
     *
     * @noinspection PhpUnused
     * @param string $name
     * @param mixed $arguments
     * @return string|null
     */
    public function compileFunction(string $name, $arguments): ?string {
        switch ($name) {
            case 'join':
                return "join(',', {$arguments})";
            case '_t':
                return sprintf('$this->translator->t(%s)', $arguments);
            default:
                return null;
        }
    }

    /**
     * Compile some filters
     *
     * @noinspection PhpUnused
     * @param string $name
     * @param $arguments
     * @return string|null
     */
    public function compileFilter(string $name, $arguments): ?string {
        switch ($name) {
            default:
                return null;
        }
    }

}
