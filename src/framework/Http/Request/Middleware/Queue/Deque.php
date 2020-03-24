<?php
/**
 * This file is part of bops
 *
 * @copyright Copyright (C) 2020 Jayson Wang
 * @license   MIT License
 * @link      https://github.com/wjiec/php-bops
 */
namespace Bops\Http\Request\Middleware\Queue;

use ArrayIterator;
use Bops\Http\Request\Middleware\MiddlewareInterface;
use IteratorAggregate;
use Traversable;


/**
 * Class Deque
 *
 * @package Bops\Http\Request\Middleware\Queue
 */
class Deque implements IteratorAggregate {

    /**
     * List of middleware
     *
     * @var MiddlewareInterface[]
     */
    protected $middleware;

    /**
     * Deque constructor.
     */
    public function __construct() {
        $this->middleware = [];
    }

    /**
     * Append a middleware to end of queue
     *
     * @param MiddlewareInterface $middleware
     * @return Deque
     */
    public function append(MiddlewareInterface $middleware) {
        $this->middleware[] = $middleware;

        return $this;
    }

    /**
     * Prepend a middle to head of queue
     *
     * @param MiddlewareInterface $middleware
     * @return Deque
     */
    public function prepend(MiddlewareInterface $middleware) {
        array_unshift($this->middleware, $middleware);

        return $this;
    }

    /**
     * Clear middleware queue
     *
     * @return $this
     */
    public function clear() {
        $this->middleware = [];

        return $this;
    }

    /**
     * Retrieve an external iterator
     *
     * @link https://php.net/manual/en/iteratoraggregate.getiterator.php
     * @return Traversable
     */
    public function getIterator() {
        return new ArrayIterator($this->middleware);
    }

}
