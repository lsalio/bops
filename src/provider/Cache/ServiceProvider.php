<?php
/**
 * This file is part of bops
 *
 * @copyright Copyright (C) 2020 Jayson Wang
 * @license   MIT License
 * @link      https://github.com/wjiec/php-bops
 */
namespace Bops\Provider\Cache;

use Bops\Cache\Pool;
use Bops\Provider\AbstractServiceProvider;
use Bops\Utils\Env\Pool\ConnectionInstaller;


/**
 * Class ServiceProvider
 *
 * @package Bops\Provider\Cache
 */
class ServiceProvider extends AbstractServiceProvider {

    /**
     * Name of the service
     *
     * @return string
     */
    public function name(): string {
        return 'cache';
    }

    /**
     * Register the service
     *
     * @return void
     */
    public function register() {
        $pool = new Pool();
        if ($primary = env('SERVICE_DATABASE_PROVIDER_PRIMARY')) {
            $pool->setPrimary($primary);
        }
        if ($writers = env('SERVICE_DATABASE_PROVIDER_WRITER')) {
            $pool->setWriters(array_map('trim', explode(',', $writers)));
        }
        if ($readers = env('SERVICE_DATABASE_PROVIDER_READERS')) {
            $pool->setReaders(array_map('trim', explode(',', $readers)));
        }

        ConnectionInstaller::register($this->name(), $pool, $this->di);
    }

}
