<?php
/**
 * This file is part of bops
 *
 * @copyright Copyright (C) 2020 Jayson Wang
 * @license   MIT License
 * @link      https://github.com/wjiec/php-bops
 */
namespace Bops\Logger\Formatter\Adapter;

use Bops\Logger\Formatter\AbstractFormatter;


/**
 * Class Plain
 *
 * @package Bops\Logger\Formatter\Adapter
 */
class Plain extends AbstractFormatter {

    /**
     * The message format
     *
     * @var string
     */
    protected $format;

    /**
     * The date format
     *
     * @var string
     */
    protected $dateFormat;

    /**
     * Plain constructor.
     *
     * @param string $format
     * @param string $dateFormat
     */
    public function __construct(string $format = '', string $dateFormat = '') {
        $this->format = $format ?: self::DEFAULT_FORMAT;
        $this->dateFormat = $dateFormat ?: self::DEFAULT_DATE_FORMAT;
    }

    /**
     * Format a message
     *
     * @param string $level
     * @param string $message
     * @param array $context
     * @return string
     */
    public function format(string $level, string $message, array $context = []): string {
        return self::interpolate($this->format, [
            'date' => date($this->dateFormat, $context['timestamp'] ?? time()),
            'type' => strtoupper($level),
            'message' => self::interpolate($message, $context) . PHP_EOL
        ]);
    }

    /**
     * @const DEFAULT_FORMAT The default format for message
     */
    public const DEFAULT_FORMAT = '{date} - {type} : {message}';

    /**
     * @const DEFAULT_DATE_FORMAT The default format for date
     */
    public const DEFAULT_DATE_FORMAT = 'D, d M y H:i:s O';

}
