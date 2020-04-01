<?php
/**
 * This file is part of bops
 *
 * @copyright Copyright (C) 2020 Jayson Wang
 * @license   MIT License
 * @link      https://github.com/wjiec/php-bops
 */
namespace Bops\Provider\Logger;

use Bops\Logger\Adapter\Stream;
use Bops\Logger\Formatter\Adapter\Plain;
use Bops\Logger\Level;
use Bops\Provider\AbstractServiceProvider;


/**
 * Class ServiceProvider
 *
 * @package Bops\Provider\Logger
 */
class ServiceProvider extends AbstractServiceProvider {

    /**
     * Name of the service
     *
     * @return string
     */
    public function name(): string {
        return 'logger';
    }

    /**
     * Register the service
     *
     * @return void
     */
    public function register() {
        $this->di->set($this->name(), function(string $filename = '') {
            if ($logger = container("logger.{$filename}")) {
                return $logger;
            }

            if (empty($filename)) {
                $filename = self::LOGGER_DEFAULT_FILENAME;
                if ($config = env('SERVICE_LOGGER_FILENAME')) {
                    $filename = rtrim($config, '\\/');
                }
            }

            $format = self::LOGGER_DEFAULT_FORMAT;
            if ($config = env('SERVICE_LOGGER_FORMAT')) {
                $format = $config;
            }

            $dateFormat = self::LOGGER_DEFAULT_DATE_FORMAT;
            if ($config = env('SERVICE_LOGGER_DATE_FORMAT')) {
                $dateFormat = $config;
            }

            $logger = new Stream(container('navigator')->logDir("{$filename}.log"));
            $logger->setFormatter(new Plain($format, $dateFormat));
            $logger->setLevel(env('SERVICE_LOGGER_LEVEL', self::LOGGER_DEFAULT_LEVEL));
            container()->setShared("logger.{$filename}", $logger);

            return $logger;
        });
    }

    /**
     * @const LOGGER_DEFAULT_LEVEL The default level of logger
     */
    protected const LOGGER_DEFAULT_LEVEL = Level::INFO;

    /**
     * @const LOGGER_DEFAULT_FILENAME The name of the default logger
     */
    protected const LOGGER_DEFAULT_FILENAME = 'application';

    /**
     * @const LOGGER_DEFAULT_FORMAT The format of the default logger
     */
    protected const LOGGER_DEFAULT_FORMAT = '{date} - {type} \t: {message}';

    /**
     * @const LOGGER_DEFAULT_DATE_FORMAT The date format of the default logger
     */
    protected const LOGGER_DEFAULT_DATE_FORMAT = 'Y-m-d H:i:s';

}
