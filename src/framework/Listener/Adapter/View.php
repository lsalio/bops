<?php
/**
 * This file is part of bops
 *
 * @noinspection PhpUnused, PhpUnusedParameterInspection
 *
 * @copyright Copyright (C) 2020 Jayson Wang
 * @license   MIT License
 * @link      https://github.com/wjiec/php-bops
 */
namespace Bops\Listener\Adapter;

use Bops\Listener\AbstractListener;
use Phalcon\Events\Event;
use Phalcon\Mvc\ViewBaseInterface;


/**
 * Class View
 *
 * @package Bops\Listener\Adapter
 */
class View extends AbstractListener {

    /**
     * Notify about not found views
     *
     * @param Event $event
     * @param ViewBaseInterface $view
     * @param string $enginePath
     */
    public function notFoundView(Event $event, ViewBaseInterface $view, string $enginePath) {
        if ($event->isCancelable()) {
            $event->stop();
        }
    }

}
