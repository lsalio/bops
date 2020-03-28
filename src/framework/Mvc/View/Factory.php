<?php
/**
 * This file is part of bops
 *
 * @copyright Copyright (C) 2020 Jayson Wang
 * @license   MIT License
 * @link      https://github.com/wjiec/php-bops
 */
namespace Bops\Mvc\View;

use Bops\Exception\Framework\Mvc\View\UnknownViewException;
use Bops\Listener\ListenerInterface;
use Bops\Mvc\View\Adapter\Html;
use Bops\Mvc\View\Adapter\Json;
use Phalcon\Mvc\View;


/**
 * Class Factory
 *
 * @package Bops\Mvc\View
 * @method static Html html(array $config = [])
 * @method static Json json(array $config = [])
 */
class Factory {

    /**
     * Factory view by renderer
     *
     * @param string $renderer
     * @param array $config
     * @return ViewInterface
     * @throws UnknownViewException
     */
    public static function factory(string $renderer, array $config = []): ViewInterface {
        $classname = sprintf('%s\Adapter\%s', __NAMESPACE__, ucfirst($renderer));
        if (!class_exists($classname)) {
            throw new UnknownViewException("The view '{$renderer}' cannot be loaded");
        }

        /* @var View $view */
        $view = new $classname();
        $view->registerEngines([
            '.volt' => container('volt', $view)
        ]);

        $view->setEventsManager(container('eventsManager'));
        if ($listener = container('view.listener')) {
            if ($listener instanceof ListenerInterface) {
                /* @see Manager::listen() */
                container('eventsManager')->listen('view', $listener);
            }
        }

        return $view->loadConfig($config);
    }

    /**
     * @param $name
     * @param $arguments
     * @return ViewInterface
     * @throws UnknownViewException
     */
    public static function __callStatic($name, $arguments) {
        return static::factory($name, empty($arguments) ? [] : current($arguments));
    }

}
