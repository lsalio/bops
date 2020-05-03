<?php
/**
 * This file is part of bops
 *
 * @copyright Copyright (C) 2020 Jayson Wang
 * @license   MIT License
 * @link      https://github.com/wjiec/php-bops
 */
namespace Bops\Provider\Modules;

use Bops\Config\Factory;
use Bops\Config\Loader\Adapter\LocalDirectory;
use Bops\Provider\AbstractServiceProvider;


/**
 * Class ServiceProvider
 *
 * @package Bops\Provider\Modules
 */
class ServiceProvider extends AbstractServiceProvider {

    /**
     * Name of the service
     *
     * @return string
     */
    public function name(): string {
        return 'modules';
    }

    /**
     * Register the service
     *
     * @return void
     */
    public function register() {
        $this->di->setShared($this->name(), function() {
            $configDir = container('navigator')->configDir();
            return (new Factory('__modules__', new LocalDirectory($configDir)))
                ->load(['modules'])->dump()
                ->get()->modules;
        });
    }

}
