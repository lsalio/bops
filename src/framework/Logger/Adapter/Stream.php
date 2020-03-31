<?php
/**
 * This file is part of bops
 *
 * @copyright Copyright (C) 2020 Jayson Wang
 * @license   MIT License
 * @link      https://github.com/wjiec/php-bops
 */
namespace Bops\Logger\Adapter;

use Bops\Exception\Framework\Logger\UnknownStreamException;
use Bops\Logger\AbstractLogger;
use Bops\Logger\Formatter\Adapter\Plain;
use Bops\Logger\Formatter\FormatterInterface;


/**
 * Class File
 *
 * @package Bops\Logger\Adapter
 */
class Stream extends AbstractLogger {

    /**
     * Log filename
     *
     * @var string
     */
    protected $filename;

    /**
     * File handler
     *
     * @var resource
     */
    protected $handler;

    /**
     * File constructor.
     *
     * @param string $filename
     * @throws UnknownStreamException
     */
    public function __construct(string $filename) {
        $this->filename = $filename;
        $this->handler = fopen($filename, 'ab');
        if (!is_resource($this->handler)) {
            throw new UnknownStreamException("Can't open log file at {$filename}");
        }
    }

    /**
     * Closes the logger
     *
     * @return bool
     */
    public function close(): bool {
        if (is_resource($this->handler)) {
            return fclose($this->handler);
        }
        return true;
    }

    /**
     * @inheritDoc
     *
     * @return FormatterInterface
     */
    protected function getDefaultFormatter(): FormatterInterface {
        return new Plain();
    }

    /**
     * @inheritDoc
     *
     * @param string $level
     * @param string $message
     * @param array $context
     * @return mixed|void
     */
    protected function doLog(string $level, string $message, array $context = []) {
        if (is_resource($this->handler)) {
            fwrite($this->handler, $this->getFormatter()->format($level, $message, $context));
        }
    }

}
