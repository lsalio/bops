<?php
/**
 * This file is part of bops
 *
 * @copyright Copyright (C) 2020 Jayson Wang
 * @license   MIT License
 * @link      https://github.com/wjiec/php-bops
 */
namespace Bops\Provider\Url;

use Bops\Provider\AbstractServiceProvider;
use Phalcon\Mvc\Url;


/**
 * Class ServiceProvider
 *
 * @package Bops\Provider\Url
 */
class ServiceProvider extends AbstractServiceProvider {

    /**
     * Name of the service
     *
     * @return string
     */
    public function name(): string {
        return 'url';
    }

    /**
     * Register the service
     *
     * @return void
     */
    public function register() {
        $this->di->setShared($this->name(), function() {
            $url = new Url();

            $url->setBaseUri('/');
            if ($baseUri = env('SERVICE_URL_BASE_URI')) {
                $url->setBaseUri($baseUri);
            }

            $url->setStaticBaseUri('/');
            if ($staticBaseUri = env('SERVICE_URL_STATIC_BASE_URI')) {
                $url->setStaticBaseUri($staticBaseUri);
            }

            return $url;
        });
    }

}
