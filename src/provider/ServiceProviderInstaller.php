<?php
/**
 * This file is part of bops
 *
 * @copyright Copyright (C) 2020 Jayson Wang
 * @license   MIT License
 * @link      https://github.com/wjiec/php-bops
 */
namespace Bops\Provider;


/**
 * Class ServiceProviderInstaller
 *
 * @package Bops\Provider
 */
class ServiceProviderInstaller {

    /**
     * Install and setup provider of service
     *
     * @param ServiceProviderInterface $provider
     */
    public static function setup(ServiceProviderInterface $provider) {
        $provider->register();
        $provider->initialize();
    }

}
