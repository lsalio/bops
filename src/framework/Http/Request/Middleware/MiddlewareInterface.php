<?php
/**
 * This file is part of bops
 *
 * @copyright Copyright (C) 2020 Jayson Wang
 * @license   MIT License
 * @link      https://github.com/wjiec/php-bops
 */
namespace Bops\Http\Request\Middleware;

use Phalcon\Http\RequestInterface;


/**
 * Interface MiddlewareInterface
 *
 * @package Bops\Http\Request\Middleware
 */
interface MiddlewareInterface {

    /**
     * Process an incoming server request.
     *
     * @param RequestInterface $request
     * @return bool
     */
    public function process(RequestInterface $request): bool;

}
