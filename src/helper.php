<?php
/**
 * This file is part of bops
 *
 * @copyright Copyright (C) 2020 Jayson Wang
 * @license   MIT License
 * @link      https://github.com/wjiec/php-bops
 */

use Phalcon\Di;
use Phalcon\DiInterface;


if (!function_exists('value_of')) {
    /**
     * Return the default value of the given value.
     *
     * @param mixed $value
     * @return mixed
     */
    function value_of($value) {
        return $value instanceof Closure ? $value() : $value;
    }
}

if (!function_exists('env')) {
    /**
     * Gets the value of an environment variable
     *
     * @param string $key
     * @param null $default
     * @return array|bool|false|mixed|string|null
     */
    function env(string $key, $default = null) {
        $value = getenv($key);
        if ($value === false) {
            return value_of($default);
        }

        switch ($value) {
            case 'true':
            case 'yes':
                return true;
            case 'false':
            case 'no':
                return false;
            case 'null':
                return null;
            case 'empty':
                return '';
        }
        return $value;
    }
}

if (!function_exists('container')) {
    /**
     * Calls the default Dependency Injection container
     *
     * @param string $service
     * @param mixed ...$args
     * @return mixed|DiInterface
     */
    function container(string $service = '', ...$args) {
        $di = Di::getDefault();
        if (!$service && empty($args)) {
            return $di;
        }

        if (!$di->has($service)) {
            return null;
        }
        return call_user_func_array([$di, 'get'], [$service, $args]);
    }
}

if (!function_exists('_t')) {
    /**
     * Get the translated string of key
     *
     * @param string $key
     * @return string
     */
    function _t(string $key): string {
        if ($translator = container('translator')) {
            return $translator->_($key);
        }
        return $key;
    }
}

if (!function_exists('_url')) {
    /**
     * Gets the url by parameters
     *
     * @param string|array|null $uri
     * @param string|array|null $args
     * @param string|array|null $local
     * @param string|null $baseUri
     * @return string
     */
    function _url($uri = null, $args = null, $local = null, $baseUri = null): string {
        /** @noinspection PhpMethodParametersCountMismatchInspection */
        return container('url')->get($uri, $args, $local, $baseUri);
    }
}

if (!function_exists('_config')) {
    /**
     * Gets the value from config service and default when key not found
     *
     * @param $paths
     * @param null|mixed $default
     * @param string $delimiter
     * @return mixed
     */
    function _config($paths, $default = null, string $delimiter = '.') {
        if (!is_array($paths)) {
            $paths = explode($delimiter, strval($paths));
        }

        if ($config = container('config')) {
            foreach ($paths as $path) {
                if (isset($config->{$path})) {
                    $config = $config->{$path};
                } else {
                    return $default;
                }
            }
            return $config;
        }
        return $default;
    }
}
