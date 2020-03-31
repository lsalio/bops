<?php
/**
 * This file is part of bops
 *
 * @copyright Copyright (C) 2020 Jayson Wang
 * @license   MIT License
 * @link      https://github.com/wjiec/php-bops
 */
namespace Bops\Logger;

use Bops\Exception\Framework\Logger\UnknownLogLevelException;
use Bops\Logger\Formatter\FormatterInterface;
use Psr\Log\AbstractLogger as PsrAbstractLogger;
use Psr\Log\InvalidArgumentException;


/**
 * Class AbstractLogger
 *
 * @package Bops\Logger
 */
abstract class AbstractLogger extends PsrAbstractLogger implements LoggerInterface {

    /**
     * Logger level
     *
     * @var string
     */
    protected $level = Level::INFO;

    /**
     * Message formatter
     *
     * @var FormatterInterface
     */
    protected $formatter;

    /**
     * Filters the logs sent to the handlers to be greater or equals than a specific level
     *
     * @param string $level
     * @return LoggerInterface
     * @throws UnknownLogLevelException
     */
    public function setLevel(string $level): LoggerInterface {
        if (!Level::contains($level)) {
            throw new UnknownLogLevelException("Unknown log level '{$level}'");
        }

        $this->level = $level;
        return $this;
    }

    /**
     * Returns the current log level
     *
     * @return string
     */
    public function getLevel(): string {
        return $this->level;
    }

    /**
     * Sets the message formatter
     *
     * @param FormatterInterface $formatter
     * @return LoggerInterface
     */
    public function setFormatter(FormatterInterface $formatter): LoggerInterface {
        $this->formatter = $formatter;
        return $this;
    }

    /**
     * Returns the internal formatter
     *
     * @return FormatterInterface
     */
    public function getFormatter(): FormatterInterface {
        if (!$this->formatter) {
            $this->formatter = $this->getDefaultFormatter();
        }
        return $this->formatter;
    }

    /**
     * Logs with an arbitrary level.
     *
     * @param mixed $level
     * @param string $message
     * @param mixed[] $context
     *
     * @return void
     * @throws InvalidArgumentException
     */
    public function log($level, $message, array $context = []) {
        if (Level::seriousOf($level, $this->level)) {
            $this->doLog($level, $message, $context);
        }
    }

    /**
     * Returns the formatter by default
     *
     * @return FormatterInterface
     */
    abstract protected function getDefaultFormatter(): FormatterInterface;

    /**
     * Logs with an arbitrary level really
     *
     * @param string $level
     * @param string $message
     * @param array $context
     * @return mixed
     */
    abstract protected function doLog(string $level, string $message, array $context = []);

}
