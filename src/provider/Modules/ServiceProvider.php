<?php
/**
 * This file is part of bops
 *
 * @copyright Copyright (C) 2020 Jayson Wang
 * @license   MIT License
 * @link      https://github.com/wjiec/php-bops
 */
namespace Bops\Provider\Modules;

use Bops\Provider\AbstractServiceProvider;
use Phalcon\Config;


/**
 * Class ServiceProvider
 *
 * @package Bops\Provider\Modules
 */
class ServiceProvider extends AbstractServiceProvider {

    /**
     * Name of the service
     *
     * @return string
     */
    public function name(): string {
        return 'modules';
    }

    /**
     * Register the service
     *
     * @return void
     */
    public function register() {
        $this->di->setShared($this->name(), function() {
            $configPath = container('navigator')->configDir('modules.php');
            if (file_exists($configPath)) {
                /** @noinspection PhpIncludeInspection */
                $modules = include $configPath;
                if (!($modules instanceof Config)) {
                    $modules = new Config($modules);
                }
                return $modules;
            }
            return new Config([]);
        });
    }

}
