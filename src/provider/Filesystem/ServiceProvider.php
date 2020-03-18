<?php
/**
 * This file is part of bops
 *
 * @copyright Copyright (C) 2020 Jayson Wang
 * @license   MIT License
 * @link      https://github.com/wjiec/php-bops
 */
namespace Bops\Provider\Filesystem;

use Bops\Provider\AbstractServiceProvider;
use League\Flysystem\Adapter\Local;
use League\Flysystem\Filesystem;


/**
 * Class ServiceProvider
 *
 * @package Bops\Provider\Filesystem
 */
class ServiceProvider extends AbstractServiceProvider {

    /**
     * Name of the service
     *
     * @return string
     */
    public function name(): string {
        return 'filesystem';
    }

    /**
     * Register the service
     *
     * @return void
     */
    public function register() {
        $this->di->set($this->name(), function(string $root) {
            return new Filesystem(new Local($root));
        });
    }

}
