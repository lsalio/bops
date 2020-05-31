<?php
/**
 * This file is part of bops
 *
 * @copyright Copyright (C) 2020 Jayson Wang
 * @license   MIT License
 * @link      https://github.com/wjiec/php-bops
 */
namespace Bops\Mvc\Controller;

use Bops\Http\Request\Middleware\MiddlewareInterface;
use Bops\Mvc\Controller;
use Throwable;


/**
 * Class AbstractErrorController
 *
 * @package Bops\Mvc\Controller
 */
abstract class AbstractErrorController extends Controller {

    /**
     * Handle on controller/action not found error
     */
    public function notFoundAction() {}

    /**
     * Handle on internal error
     *
     * @param Throwable $e
     */
    public function internalAction(Throwable $e) {}

    /**
     * Handle on middleware error
     *
     * @param MiddlewareInterface $middleware
     */
    public function middlewareAction(MiddlewareInterface $middleware) {}

}
