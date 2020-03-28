<?php
/**
 * This file is part of bops
 *
 * @copyright Copyright (C) 2020 Jayson Wang
 * @license   MIT License
 * @link      https://github.com/wjiec/php-bops
 */
namespace Bops\Http\Request\Middleware\Queue;

use Bops\Http\Request\Middleware\MiddlewareInterface;
use IteratorAggregate;
use SplPriorityQueue;
use Traversable;


/**
 * Class PriorityQueue
 *
 * @package Bops\Http\Request\Middleware\Queue
 */
class PriorityQueue implements IteratorAggregate {

    /**
     * priority queue
     *
     * @var SplPriorityQueue
     */
    protected $queue;

    /**
     * Deque constructor.
     */
    public function __construct() {
        $this->queue = new SplPriorityQueue();
        $this->queue->setExtractFlags(SplPriorityQueue::EXTR_DATA);
    }

    /**
     * Append a middleware to end of queue
     *
     * @param MiddlewareInterface $middleware
     * @param int $priority
     * @return PriorityQueue
     */
    public function append(MiddlewareInterface $middleware, int $priority = 0) {
        $this->queue->insert($middleware, $priority);

        return $this;
    }

    /**
     * Prepend a middle to head of queue
     *
     * @param MiddlewareInterface $middleware
     * @param int $priority
     * @return PriorityQueue
     */
    public function prepend(MiddlewareInterface $middleware, int $priority = 100) {
        $this->queue->insert($middleware, $priority);

        return $this;
    }

    /**
     * Clear middleware queue
     *
     * @return $this
     */
    public function clear() {
        $this->queue = new SplPriorityQueue();
        $this->queue->setExtractFlags(SplPriorityQueue::EXTR_DATA);

        return $this;
    }

    /**
     * Retrieve an external iterator
     *
     * @return Traversable
     * @since 5.0.0
     */
    public function getIterator() {
        return clone $this->queue;
    }

}
