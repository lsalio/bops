<?php
/**
 * This file is part of bops
 *
 * @copyright Copyright (C) 2020 Jayson Wang
 * @license   MIT License
 * @link      https://github.com/wjiec/php-bops
 */
namespace Bops\Provider\Session;

use Bops\Provider\AbstractServiceProvider;
use Bops\Utils\Env\Config\Loader;
use Phalcon\Session\Factory;


/**
 * Class ServiceProvider
 *
 * @package Bops\Provider\Session
 */
class ServiceProvider extends AbstractServiceProvider {

    /**
     * Name of the service
     *
     * @return string
     */
    public function name(): string {
        return 'session';
    }

    /**
     * Register the service
     *
     * @return void
     */
    public function register() {
        $this->di->setShared($this->name(), function() {
            $config = Loader::load('SERVICE_SESSION_');
            if (!empty($config['unique_id'])) {
                $config['uniqueId'] = $config['unique_id'];
                unset($config['unique_id']);
            }
            $session = Factory::load($config);
            $session->start();

            return $session;
        });
    }

}
