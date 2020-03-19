<?php
/**
 * This file is part of bops
 *
 * @copyright Copyright (C) 2020 Jayson Wang
 * @license   MIT License
 * @link      https://github.com/wjiec/php-bops
 */
namespace Bops\Provider\Logger;

use Bops\Provider\AbstractServiceProvider;
use Phalcon\Logger;
use Phalcon\Logger\Adapter\File as FileLogger;
use Phalcon\Logger\Formatter\Line as LineFormatter;


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
            $level = self::LOGGER_DEFAULT_LEVEL;
            if ($config = _config('logger.level')) {
                switch (strtolower($config)) {
                    case 'emergency': $level = Logger::EMERGENCY; break;
                    case 'critical': $level = Logger::CRITICAL; break;
                    case 'alert': $level = Logger::ALERT; break;
                    case 'error': $level = Logger::ERROR; break;
                    case 'warning': $level = Logger::WARNING; break;
                    case 'notice': $level = Logger::NOTICE; break;
                    case 'info': $level = Logger::INFO; break;
                    case 'debug': $level = Logger::DEBUG; break;
                }
            }

            if (empty($filename)) {
                $filename = self::LOGGER_DEFAULT_FILENAME;
                if ($config = _config('logger.filename')) {
                    $filename = rtrim($config, '\\/');
                }
            }

            $format = self::LOGGER_DEFAULT_FORMAT;
            if ($config = _config('logger.format')) {
                $format = $config;
            }

            $dateFormat = self::LOGGER_DEFAULT_DATE_FORMAT;
            if ($config = _config('logger.date')) {
                $dateFormat = $config;
            }

            $logger = new FileLogger(container('navigator')->logDir("{$filename}.log"));
            $logger->setFormatter(new LineFormatter($format, $dateFormat));
            $logger->setLogLevel($level);
        });
    }

    /**
     * @const LOGGER_DEFAULT_LEVEL The default level of logger
     */
    protected const LOGGER_DEFAULT_LEVEL = Logger::WARNING;

    /**
     * @const LOGGER_DEFAULT_FILENAME The name of the default logger
     */
    protected const LOGGER_DEFAULT_FILENAME = 'application';

    /**
     * @const LOGGER_DEFAULT_FORMAT The format of the default logger
     */
    protected const LOGGER_DEFAULT_FORMAT = '%date% - %type% \t: %message%';

    /**
     * @const LOGGER_DEFAULT_DATE_FORMAT The date format of the default logger
     */
    protected const LOGGER_DEFAULT_DATE_FORMAT = 'Y-m-d H:i:s';

}
