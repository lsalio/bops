<?php
/**
 * This file is part of bops
 *
 * @copyright Copyright (C) 2020 Jayson Wang
 * @license   MIT License
 * @link      https://github.com/wjiec/php-bops
 */
namespace Bops\Mvc;

use Bops\Application\ApplicationInterface;
use Bops\Listener\ListenerInterface;
use Phalcon\DiInterface;
use Phalcon\Http\ResponseInterface;
use Phalcon\Mvc\Application as MvcApplication;


/**
 * Class Application
 *
 * @package Bops\Mvc
 */
class Application extends MvcApplication implements ApplicationInterface {

    /**
     * Initializing the modules for application
     *
     * @param DiInterface|null $di
     */
    public function __construct(DiInterface $di = null) {
        parent::__construct(($di ?: container()));

        $this->setEventsManager(container('eventsManager'));
        if ($listener = container('application.listener')) {
            if ($listener instanceof ListenerInterface) {
                /* @see Manager::listen() */
                container('eventsManager')->listen('application', $listener);
            }
        }

        $this->setupModules();
    }

    /**
     * @param null $uri
     * @return ResponseInterface
     */
    public function handle($uri = null): ResponseInterface {
        /* @see Router::getVersionFeatureUri() */
        return parent::handle(container('router')->getVersionFeatureUri($uri));
    }

    /**
     * Setups the multi modules supported, and register all modules
     * to the application
     */
    private function setupModules() {
        if ($module = env('SERVICE_APPLICATION_MODULE_DEFAULT')) {
            $this->setDefaultModule($module);
        }

        if ($modules = container('modules')->toArray()) {
            $this->registerModules($modules);
        }
    }

}
