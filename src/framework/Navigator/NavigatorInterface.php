<?php
/**
 * This file is part of bops
 *
 * @copyright Copyright (C) 2020 Jayson Wang
 * @license   MIT License
 * @link      https://github.com/wjiec/php-bops
 */
namespace Bops\Navigator;


/**
 * Interface NavigatorInterface
 *
 * @package Bops\Navigator
 */
interface NavigatorInterface {

    /**
     * The directory path of the document root
     *
     * @param string $path
     * @return string
     */
    public function rootDir(string $path = ''): string;

    /**
     * The directory path of the application
     *
     * @param string $path
     * @return string
     */
    public function applicationDir(string $path = ''): string;

    /**
     * The directory path of the configure
     *
     * @param string $path
     * @return string
     */
    public function configDir(string $path = ''): string;

    /**
     * The directory path of the locale files
     *
     * @param string $path
     * @return string
     */
    public function localeDir(string $path = ''): string;

    /**
     * The directory of the modules
     *
     * @param string $path
     * @return string
     */
    public function moduleDir(string $path = ''): string;

    /**
     * The directory path of the storage
     *
     * @param string $path
     * @return string
     */
    public function storageDir(string $path = ''): string;

    /**
     * The directory path of storage
     *
     * @param string $path
     * @return string
     */
    public function cacheDir(string $path = ''): string;

    /**
     * The directory path of the storage to cache config
     *
     * @param string $path
     * @return string
     */
    public function configCacheDir(string $path = ''): string;

    /**
     * The directory path of the storage to cache compiled volt
     *
     * @param string $path
     * @return string
     */
    public function voltCacheDir(string $path = ''): string;

    /**
     * The directory path of the storage to cache logs file
     *
     * @param string $path
     * @return string
     */
    public function logDir(string $path = ''): string;

}
