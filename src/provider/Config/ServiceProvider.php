<?php
/**
 * This file is part of bops
 *
 * @copyright Copyright (C) 2020 Jayson Wang
 * @license   MIT License
 * @link      https://github.com/wjiec/php-bops
 */
namespace Bops\Provider\Config;

use Bops\Config\Factory;
use Bops\Config\Loader\Adapter\PathJoiner;
use Bops\Provider\AbstractServiceProvider;
use League\Flysystem\Filesystem;


/**
 * Class ServiceProvider
 *
 * @package Bops\Provider\Config
 */
class ServiceProvider extends AbstractServiceProvider {

    /**
     * Name of the service
     *
     * @return string
     */
    public function name(): string {
        return 'config';
    }

    /**
     * Register the service
     *
     * @return void
     */
    public function register() {
        $this->di->setShared($this->name(), function() {
            /* @var Filesystem $filesystem */
            $filesystem = container('filesystem', container('navigator')->configDir());
            $factory = new Factory('global', new PathJoiner(container('navigator')->configDir()));
            if ($filesystem->has('config.php')) {
                $factory->load(['config']);
            }

            if ($configs = env('SERVICE_CONFIG_MODULES')) {
                $factory->load(array_map('trim', explode(',', $configs)), true);
            }

            return $factory->dump()->get();
        });
    }

}
