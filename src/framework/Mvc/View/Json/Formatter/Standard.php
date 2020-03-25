<?php
/**
 * This file is part of bops
 *
 * @copyright Copyright (C) 2020 Jayson Wang
 * @license   MIT License
 * @link      https://github.com/wjiec/php-bops
 */
namespace Bops\Mvc\View\Json\Formatter;

use Bops\Di\Component;


/**
 * Trait Standard
 *
 * @package Bops\Mvc\View\Json\Formatter
 * @mixin Component
 */
trait Standard {

    /**
     * Make a success response to client
     *
     * @param array $data
     * @param array $extend
     */
    protected function success(?array $data, array $extend = []) {
        $this->view->setVars(array_merge(['data' => $data], $extend), false);
    }

    /**
     * Make a error response to client
     *
     * @param string $message
     * @param string $code
     * @param array $extend
     */
    protected function error(string $message, string $code, array $extend = []) {
        $this->view->setVars(array_merge([
            'error' => [
                'code' => $code,
                'message' => $message
            ]
        ], $extend), false);
    }

}
