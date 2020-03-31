<?php
/**
 * This file is part of bops
 *
 * @copyright Copyright (C) 2020 Jayson Wang
 * @license   MIT License
 * @link      https://github.com/wjiec/php-bops
 */
namespace Bops\Logger;

use ReflectionClass;
use ReflectionException;


/**
 * Class Level
 *
 * @package Bops\Logger
 */
class Level {

    /**
     * The constants of level
     *
     * @var array
     */
    private static $constants;

    /**
     * Checks level defined
     *
     * @param string $level
     * @return bool
     */
    public static function contains(string $level): bool {
        return isset(self::getConstants()[$level]);
    }

    /**
     * Checks level serious
     *
     * @param string $left
     * @param string $right
     * @return bool
     */
    public static function seriousOf(string $left, string $right): bool {
        return self::getConstants()[$left]['weight'] >= self::getConstants()[$right]['weight'];
    }

    /**
     * Returns constants definition
     *
     * @return array
     */
    protected static function getConstants(): array {
        if (self::$constants === null) {
            self::$constants = [];

            try {
                $ref = new ReflectionClass(static::class);
                foreach ($ref->getReflectionConstants() as $index => $constant) {
                    self::$constants[$constant->getValue()] = [
                        'weight' => self::parseConstantWeight($constant->getDocComment())
                    ];
                }
            } catch (ReflectionException $e) {}
        }
        return self::$constants;
    }

    /**
     * Returns weight for log level
     *
     * @param string $comment
     * @param int $default
     * @return int
     */
    private static function parseConstantWeight(string $comment, int $default = 0): int {
        if (preg_match_all('/@weight\s*(?<weight>\d+)$/m', $comment, $matches) !== false) {
            if (isset($matches['weight'][0])) {
                return (int)$matches['weight'][0];
            }
        }
        return $default;
    }

    /**
     * @weight 100
     */
    public const EMERGENCY = 'emergency';

    /**
     * @weight 90
     */
    public const ALERT     = 'alert';

    /**
     * @weight 80
     */
    public const CRITICAL  = 'critical';

    /**
     * @weight 70
     */
    public const ERROR     = 'error';

    /**
     * @weight 60
     */
    public const WARNING   = 'warning';

    /**
     * @weight 50
     */
    public const NOTICE    = 'notice';

    /**
     * @weight 40
     */
    public const INFO      = 'info';

    /**
     * @weight 10
     */
    public const DEBUG     = 'debug';

}
