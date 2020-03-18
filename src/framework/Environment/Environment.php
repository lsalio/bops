<?php
/**
 * This file is part of bops
 *
 * @copyright Copyright (C) 2020 Jayson Wang
 * @license   MIT License
 * @link      https://github.com/wjiec/php-bops
 */
namespace Bops\Environment;


/**
 * Class Environment
 *
 * @package Bops\Environment
 */
class Environment {

    /**
     * @var string
     */
    protected $environment;

    /**
     * Environment constructor.
     *
     * @param string $environment
     */
    public function __construct(string $environment) {
        $this->environment = $environment;
    }

    /**
     * Returns true when current environment has in params
     *
     * @param string[]|mixed $envs
     * @return bool
     */
    public function contains(string ...$envs): bool {
        return in_array($this->environment, $envs);
    }

    /**
     * Returns a string of the current environment
     *
     * @return string
     */
    public function value(): string {
        return $this->environment;
    }

    /**
     * Returns a string of the current environment
     *
     * @return string
     */
    public function __toString(): string {
        return $this->environment;
    }

}
