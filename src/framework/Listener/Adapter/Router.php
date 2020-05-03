<?php
/**
 * This file is part of bops
 *
 * @noinspection PhpUnusedParameterInspection, PhpUnused
 *
 * @copyright Copyright (C) 2020 Jayson Wang
 * @license   MIT License
 * @link      https://github.com/wjiec/php-bops
 */
namespace Bops\Listener\Adapter;

use Bops\Listener\AbstractListener;
use Bops\Mvc\Router\Router as MvcRouter;
use Phalcon\Events\Event;


/**
 * Class Router
 *
 * @package Bops\Listener\Adapter
 */
class Router extends AbstractListener {

    /**
     * Module not found in application hot-fix
     *
     * @param Event $event
     * @param MvcRouter $router
     */
    public function afterCheckRoutes(Event $event, MvcRouter $router) {
        if ($module = $router->getModuleName()) {
            if (!isset(container('modules')->{$module})) {
                if ($errorModule = env('BOPS_ERROR_FORWARD_MODULE')) {
                    container('application')->registerModules([
                        $module => container('application')->getModule($errorModule)
                    ], true);

                    if ($controller = env('BOPS_ERROR_FORWARD_CONTROLLER', 'error')) {
                        $router->setControllerName($controller);
                    }

                    if ($action = env('BOPS_ERROR_FORWARD_NOT_FOUND', 'notFound')) {
                        $router->setActionName($action);
                    }
                }
            }
        }
    }

}
