<?php
/**
 * This file is part of bops
 *
 * @copyright Copyright (C) 2020 Jayson Wang
 * @license   MIT License
 * @link      https://github.com/wjiec/php-bops
 */
namespace Bops\Provider\ErrorHandler;

use Bops\Error\Handler\Logger;
use Bops\Provider\AbstractServiceProvider;
use Whoops\Handler\PrettyPageHandler as PrettyPage;
use Whoops\Run;


/**
 * Class ServiceProvider
 *
 * @package Bops\Provider\ErrorHandler
 */
class ServiceProvider extends AbstractServiceProvider {

    /**
     * Name of the service
     *
     * @return string
     */
    public function name(): string {
        return 'errorHandler';
    }

    /**
     * Register the service
     *
     * @return void
     */
    public function register() {
        $service = $this->name();
        $this->di->setShared("{$service}.logger", Logger::class);
        $this->di->setShared("{$service}.prettyPage", PrettyPage::class);

        $this->di->setShared($service, function() use ($service) {
            $run = new Run();
            $run->appendHandler(container("{$service}.logger"));

            if (env('BOPS_DEBUG', false)) {
                $run->appendHandler(container("{$service}.prettyPage"));
            }

            return $run;
        });
    }

    /**
     * Initializing the service
     *
     * @return void
     */
    public function initialize() {
        /* @var Run $run */
        if ($run = container($this->name())) {
            $run->register();
        }
    }

}
