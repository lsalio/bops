<?php
/**
 * This file is part of bops
 *
 * @copyright Copyright (C) 2020 Jayson Wang
 * @license   MIT License
 * @link      https://github.com/wjiec/php-bops
 */
namespace Bops\Module;

use Bops\Config\Factory as ConfigFactory;
use Bops\Config\Loader\Adapter\LocalDirectory;
use Bops\Mvc\Dispatcher\Factory as DispatcherFactory;
use Bops\Mvc\View\Factory as ViewFactory;
use Bops\Provider\ServiceProviderInstaller;
use Phalcon\Config;
use Phalcon\DiInterface;


/**
 * Class AbstractModule
 *
 * @package Bops\Module
 */
abstract class AbstractModule implements ModuleInterface {

    /**
     * Registers services related to the module
     *
     * @param DiInterface $di
     */
    public function registerServices(DiInterface $di) {
        $this->setupModuleConfig($di);
        $this->setupDispatcher($di);
        $this->setupView($di);

        foreach ($this->serviceProviders() as $provider) {
            ServiceProviderInstaller::setup(new $provider());
        }
    }

    /**
     * Returns an array of the service providers
     *
     * @return string[]
     */
    protected function serviceProviders(): array {
        return [];
    }

    /**
     * Returns the name of the current module
     *
     * @return string
     */
    abstract protected function moduleName(): string;

    /**
     * The name of the module-config service
     *
     * @return string
     */
    protected function configServiceName(): string {
        return 'moduleConfig';
    }

    /**
     * Returns the path of module configure dir
     *
     * @return string
     */
    abstract protected function configDir(): string;

    /**
     * Returns an array of the config module filename without extname
     *
     * @return array
     */
    protected function configModules(): array {
        return [];
    }

    /**
     * Setup the configure of current module
     *
     * @param DiInterface $di
     */
    protected function setupModuleConfig(DiInterface $di): void {
        $serviceName = $this->configServiceName();
        if (!empty($this->configDir()) && !empty($serviceName)) {
            $configs = $this->configModules();
            $name = str_replace('\\', '_', strtolower(get_class($this)));
            $di->setShared($serviceName, function() use ($name, $configs) {
                return (new ConfigFactory($name, new LocalDirectory($this->configDir())))
                    ->load(array_merge(['config'], $configs));
            });
        } else {
            $di->setShared($serviceName, new Config());
        }
    }

    /**
     * Setup the dispatcher service from moduleConfig
     *
     * @param DiInterface $di
     */
    private function setupDispatcher(DiInterface $di): void {
        if (isset(container('moduleConfig')->module->dispatcher)) {
            /* @var $config Config */
            if ($config = container('moduleConfig')->module->dispatcher) {
                $module = $this->moduleName();
                $di->setShared('dispatcher', function() use ($module, $config) {
                    return DispatcherFactory::factory($module, $config->toArray());
                });
            }
        }
    }

    /**
     * Setup the view service from moduleConfig
     *
     * @param DiInterface $di
     */
    private function setupView(DiInterface $di): void {
        if (isset(container('moduleConfig')->module->view)) {
            if ($config = container('moduleConfig')->module->view) {
                if ($config->uses === true || $config->uses === 'html') {
                    /* @var $config Config */
                    $di->setShared('view', function() use ($config) {
                        return ViewFactory::html($config->toArray());
                    });
                } else if ($config->uses === 'json') {
                    /* @var $config Config */
                    $di->setShared('view', function() use ($config) {
                        return ViewFactory::json($config->toArray());
                    });
                } else {
                    // disabled view implicit
                    container('app')->useImplicitView(false);
                }
            }
        }
    }

}
