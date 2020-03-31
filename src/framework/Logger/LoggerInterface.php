<?php
/**
 * This file is part of bops
 *
 * @copyright Copyright (C) 2020 Jayson Wang
 * @license   MIT License
 * @link      https://github.com/wjiec/php-bops
 */
namespace Bops\Logger;

use Bops\Logger\Formatter\FormatterInterface;
use Psr\Log\LoggerInterface as PsrLoggerInterface;


/**
 * Interface LoggerInterface
 *
 * @package Bops\Logger
 */
interface LoggerInterface extends PsrLoggerInterface {

    /**
     * Filters the logs sent to the handlers to be greater or equals than a specific level
     *
     * @param string $level
     * @return LoggerInterface
     */
    public function setLevel(string $level): LoggerInterface;

    /**
     * Returns the current log level
     *
     * @return string
     */
    public function getLevel(): string;

    /**
     * Sets the message formatter
     *
     * @param FormatterInterface $formatter
     * @return LoggerInterface
     */
    public function setFormatter(FormatterInterface $formatter): LoggerInterface;

    /**
     * Returns the internal formatter
     *
     * @return FormatterInterface
     */
    public function getFormatter(): FormatterInterface;

    /**
     * Closes the logger
     *
     * @return bool
     */
    public function close(): bool;

}
