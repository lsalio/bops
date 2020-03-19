<?php
/**
 * This file is part of bops
 *
 * @copyright Copyright (C) 2020 Jayson Wang
 * @license   MIT License
 * @link      https://github.com/wjiec/php-bops
 */
namespace Bops\Mvc\Router;

use Bops\Http\Uri\Version;
use Phalcon\Mvc\Router as MvcRouter;


/**
 * Class Router
 *
 * @package Bops\Mvc\Router
 */
class Router extends MvcRouter {

    /**
     * Router constructor.
     *
     * @param bool $defaultRoutes
     */
    public function __construct($defaultRoutes = false) {
        parent::__construct($defaultRoutes);

        $this->initModuleRoutes();
        $this->initNotFoundAction();
    }

    /**
     * Gets uri from request override by versionFeature
     *
     * @see Router::getRewriteUri()
     * @return string
     */
    public function getVersionFeatureUri(): string {
        $uri = $this->getRewriteUri();
        if (preg_match('/^\/v(?<version>\d+)(?<uri>.*)/', $uri, $matches)) {
            $trimmed_uri = $matches['uri'] ?: '/';
            $module = container('app')->getDefaultModule();
            if (preg_match('/\/(?<module>\w+)\/?.*/', $trimmed_uri, $paths)) {
                $module = $paths['module'];
            }

            /* @var Version $version */
            $version = container('versionUri');
            if ($version->strictCheck($module)) {
                $version->setNumber((int)$matches['version']);
                return $trimmed_uri;
            }
        }

        return $uri;
    }

    /**
     * Initializing the routes for module supports
     *
     * @return void
     */
    protected function initModuleRoutes() {
        $this->add('/:module/:controller/:action/:params', [
            'module' => 1,
            'controller' => 2,
            'action' => 3,
            'params' => 4
        ]);
        $this->add('/:module/:controller', [
            'module' => 1,
            'controller' => 2,
        ]);
        $this->add('/:module', [
            'module' => 1
        ]);

        if ($module = env('MODULE_DEFAULT')) {
            $this->add('/', [
                'module' => $module
            ]);
        }
    }

    /**
     * Initializing the action when route not found
     *
     * @return void
     */
    protected function initNotFoundAction() {
        $error = container('config')->notFound;
        if (isset($error->controller) && isset($error->action)) {
            $this->notFound([
                'controller' => $error->controller,
                'action' => $error->action
            ]);
        }
    }

}
