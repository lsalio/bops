<?php
/**
 * This file is part of bops
 *
 * @copyright Copyright (C) 2020 Jayson Wang
 * @license   MIT License
 * @link      https://github.com/wjiec/php-bops
 */
namespace Bops\Mvc\Dispatcher;

use Bops\Listener\ListenerInterface;


/**
 * Class Factory
 *
 * @package Bops\Mvc\Dispatcher
 */
class Factory {

    /**
     * Make a dispatcher with listener
     *
     * @param string $module
     * @param array $config
     * @return Dispatcher
     */
    public static function factory(string $module, array $config = []): Dispatcher {
        $dispatcher = new Dispatcher();

        $dispatcher->setEventsManager(container('eventsManager'));
        if ($listener = container('dispatcher.listener')) {
            if ($listener instanceof ListenerInterface) {
                container('eventsManager')->attach('dispatch', $listener);
            }
        }

        return $dispatcher->loadConfig($config, $module);
    }

}
