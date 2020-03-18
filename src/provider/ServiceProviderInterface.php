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
 * Interface ServiceProviderInterface
 *
 * @package Bops\Provider
 */
interface ServiceProviderInterface {

    /**
     * Name of the service
     *
     * @return string
     */
    public function name(): string;

    /**
     * Register the service
     *
     * @return void
     */
    public function register();

    /**
     * Initializing the service
     *
     * @return void
     */
    public function initialize();

}
