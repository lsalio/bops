<?php
/**
 * This file is part of bops
 *
 * @copyright Copyright (C) 2020 Jayson Wang
 * @license   MIT License
 * @link      https://github.com/wjiec/php-bops
 */
namespace Bops\Provider\VersionUri;

use Bops\Http\Uri\Version;
use Bops\Provider\AbstractServiceProvider;


/**
 * Class ServiceProvider
 *
 * @package Bops\Provider\VersionUri
 */
class ServiceProvider extends AbstractServiceProvider {

    /**
     * Name of the service
     *
     * @return string
     */
    public function name(): string {
        return 'versionUri';
    }

    /**
     * Register the service
     *
     * @return void
     */
    public function register() {
        $this->di->setShared($this->name(), function() {
            return new Version();
        });
    }

}
