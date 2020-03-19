<?php
/**
 * This file is part of bops
 *
 * @copyright Copyright (C) 2020 Jayson Wang
 * @license   MIT License
 * @link      https://github.com/wjiec/php-bops
 */
namespace Bops\Provider\Translator;

use Bops\Provider\AbstractServiceProvider;
use Bops\Translator\Factory;


/**
 * Class ServiceProvider
 *
 * @package Bops\Provider\Translator
 */
class ServiceProvider extends AbstractServiceProvider {

    /**
     * Name of the service
     *
     * @return string
     */
    public function name(): string {
        return 'translator';
    }

    /**
     * Register the service
     *
     * @return void
     */
    public function register() {
        $this->di->set($this->name(), function(string $language = '') {
            return Factory::factory($language);
        });
    }

}
