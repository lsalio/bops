<?php
/**
 * This file is part of bops
 *
 * @copyright Copyright (C) 2020 Jayson Wang
 * @license   MIT License
 * @link      https://github.com/wjiec/php-bops
 */
namespace Bops\Provider\Router;

use Bops\Listener\ListenerInterface;
use Bops\Mvc\Router\Router;
use Bops\Provider\AbstractServiceProvider;


/**
 * Class ServiceProvider
 *
 * @package Bops\Provider\Router
 */
class ServiceProvider extends AbstractServiceProvider {

    /**
     * Name of the service
     *
     * @return string
     */
    public function name(): string {
        return 'router';
    }

    /**
     * Register the service
     *
     * @return void
     */
    public function register() {
        $this->di->setShared($this->name(), function() {
            $router = new Router();
            if (!isset($_GET['_url'])) {
                $router->setUriSource(Router::URI_SOURCE_SERVER_REQUEST_URI);
            }

            $router->removeExtraSlashes(true);
            $router->setEventsManager(container('eventsManager'));
            if ($listener = container('router.listener')) {
                if ($listener instanceof ListenerInterface) {
                    container('eventsManager')->attach('router', $listener);
                }
            }

            return $router;
        });
    }

}
