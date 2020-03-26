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
     * Gets uri from request override by versionUri
     *
     * @param string $uri
     * @return string
     * @see Router::getRewriteUri()
     */
    public function getVersionFeatureUri(string $uri = ''): string {
        $uri = $uri ?: $this->getRewriteUri();
        if (preg_match('/^\/v(?<version>\d+)(?<uri>.*)/', $uri, $matches)) {
            $trimmedUri = $matches['uri'] ?: '/';
            $module = container('application')->getDefaultModule();
            if (preg_match('/\/(?<module>\w+)\/?.*/', $trimmedUri, $paths)) {
                $module = $paths['module'];
            }

            /* @var Version $version */
            $version = container('versionUri');
            if ($version->strictCheck($module)) {
                $version->setNumber((int)$matches['version']);
                return $trimmedUri;
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
        if ($controller = env('BOPS_ERROR_FORWARD_CONTROLLER', 'error')) {
            if ($action = env('BOPS_ERROR_FORWARD_NOT_FOUND', 'notFound')) {
                $this->notFound([
                    'module' => env('BOPS_ERROR_FORWARD_MODULE'),
                    'controller' => $controller,
                    'action' => $action
                ]);
            }
        }
    }

}
