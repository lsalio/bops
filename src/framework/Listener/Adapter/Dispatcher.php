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

use Bops\Http\Request\Middleware\MiddlewareInterface;
use Bops\Http\Uri\Version;
use Bops\Listener\AbstractListener;
use Bops\Mvc\Controller\Tagging\Deeper;
use Exception;
use Phalcon\Events\Event;
use Phalcon\Mvc\Dispatcher as MvcDispatcher;
use Phalcon\Text;
use Throwable;
use Whoops\Run;


/**
 * Class Dispatcher
 *
 * @package Bops\Listener\Adapter
 */
class Dispatcher extends AbstractListener {

    /**
     * Trigger before on handler execute
     *
     * @param Event $event
     * @param MvcDispatcher $dispatcher
     * @return bool|void
     */
    public function beforeExecuteRoute(Event $event, MvcDispatcher $dispatcher) {
        if ($controller = env('BOPS_ERROR_FORWARD_CONTROLLER', 'error')) {
            if ($dispatcher->getControllerName() !== $controller) {
                return;
            }
        }

        if ($deque = container('middlewareQueue')) {
            /* @var MiddlewareInterface $middleware */
            foreach ($deque as $middleware) {
                try {
                    if ($middleware->process(container('request'))) {
                        continue;
                    }
                } catch (Throwable $e) {
                    container('logger', 'middleware')
                        ->error(sprintf("Middleware '%s' error occurs: %s",
                            get_class($middleware), $e->getMessage()));
                }

                if ($controller = env('BOPS_ERROR_FORWARD_CONTROLLER', 'error')) {
                    if ($action = env('BOPS_ERROR_FORWARD_MIDDLEWARE_ERROR', 'middleware')) {
                        return $dispatcher->forward([
                            'controller' => $controller,
                            'action' => $action,
                            'params' => [$middleware]
                        ]);
                    }
                }
            }
        }
    }

    /**
     * Triggered before the dispatcher throws any exception
     *
     * @param Event $event
     * @param MvcDispatcher $dispatcher
     * @param Throwable $exception
     * @return bool|void
     * @throws Exception
     */
    public function beforeException(Event $event, MvcDispatcher $dispatcher, Throwable $exception) {
        /* @var Version $version */
        $version = container('versionUri');
        if ($version->isVersionHandlerException($exception)) {
            do {
                if ($version->getNumber() !== 1) {
                    if ($controller = env('BOPS_ERROR_FORWARD_CONTROLLER', 'error')) {
                        if ($dispatcher->getControllerName() !== $controller) {
                            break;
                        }
                    }
                }

                if ($version->check($dispatcher->getModuleNameWithDefault())) {
                    $dispatcher->forward([
                        'namespace' => $version->namespaceFallback($dispatcher->getDefaultNamespace())
                    ]);

                    // automatically reset status code when version-feature forward
                    container('response')->setStatusCode(200);
                    return false;
                }
            } while (false);
        }

        $this->doErrorForward($dispatcher, $exception);
        // Returns false to dispatch loop again
        return false;
    }

    /**
     * Checks the loop can be deeper
     *
     * @param Event $event
     * @param MvcDispatcher $dispatcher
     */
    public function beforeNotFoundAction(Event $event, MvcDispatcher $dispatcher) {
        if (get_class($dispatcher->getActiveController()) === $dispatcher->getHandlerClass()) {
            if (container($dispatcher->getHandlerClass()) instanceof Deeper) {
                $this->doDeeperForward($dispatcher);
            }
        }
    }

    /**
     * Detect a corrected error forward and dispatch it
     *
     * @param MvcDispatcher $dispatcher
     * @param Throwable $exception
     */
    protected function doErrorForward(MvcDispatcher $dispatcher, Throwable $exception) {
        try {
            if ($dispatcher->getNamespaceName()) {
                if ($controller = env('BOPS_ERROR_FORWARD_CONTROLLER', 'error')) {
                    if ($action = env('BOPS_ERROR_FORWARD_INTERNAL_ERROR', 'internal')) {
                        return $dispatcher->forward([
                            'controller' => $controller,
                            'action' => $action,
                            'params' => [$exception]
                        ]);
                    }
                }
            }
        } catch (Throwable $e) {
            container('logger', 'sys')->error($e->getMessage());
        }

        /* @see Run::handleException() */
        container('errorHandler')->handleException($exception);
        if ($statusCode = env('BOPS_ERROR_HTTP_STATUS_CODE', 404)) {
            container('response')->setStatusCode($statusCode);
        }
        return $dispatcher->setReturnedValue(env('BOPS_ERROR_HTTP_MESSAGE', ''));
    }

    /**
     * Deeper forward and dispatch it
     *
     * @param MvcDispatcher $dispatcher
     */
    protected function doDeeperForward(MvcDispatcher $dispatcher) {
        $params = $dispatcher->getParams();
        $dispatcher->forward([
            'namespace' => join('\\', [
                $dispatcher->getNamespaceName(),
                Text::camelize($dispatcher->getControllerName())
            ]),
            'controller' => $dispatcher->getActionName(),
            'action' => empty($params) ? $dispatcher->getDefaultAction() : array_shift($params),
            'params' => $params
        ]);
    }

}
