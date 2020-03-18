<?php
/**
 * This file is part of bops
 *
 * @copyright Copyright (C) 2020 Jayson Wang
 * @license   MIT License
 * @link      https://github.com/wjiec/php-bops
 */
namespace Bops\Provider\Environment;

use Bops\Environment\Environment;
use Bops\Provider\AbstractServiceProvider;


/**
 * Class ServiceProvider
 *
 * @package Bops\Provider\Environment
 */
class ServiceProvider extends AbstractServiceProvider {

    /**
     * Name of the service
     *
     * @return string
     */
    public function name(): string {
        return 'environment';
    }

    /**
     * Register the service
     *
     * @return void
     */
    public function register() {
        $this->di->setShared($this->name(), function(string $environment) {
            return new Environment($environment);
        });
    }

}
