<?php
/**
 * This file is part of bops
 *
 * @copyright Copyright (C) 2020 Jayson Wang
 * @license   MIT License
 * @link      https://github.com/wjiec/php-bops
 */
namespace Bops\Navigator\Adapter;

use Bops\Navigator\NavigatorInterface;


/**
 * Class Standard
 *
 * @package Bops\Navigator\Adapter
 */
class Standard implements NavigatorInterface {

    /**
     * The directory path of the document root
     *
     * @var string
     */
    protected $root;

    /**
     * Standard constructor.
     *
     * @param string $root
     */
    public function __construct(string $root) {
        $this->root = trim($root, '\\/');
    }

    /**
     * The directory path of the document root
     *
     * @param string $path
     * @return string
     */
    public function rootDir(string $path = ''): string {
        return $this->root . ($path ? "/{$path}" : '');
    }

    /**
     * The directory path of the application
     *
     * @param string $path
     * @return string
     */
    public function applicationDir(string $path = ''): string {
        return $this->rootDir('app') . ($path ? "/{$path}" : '');
    }

    /**
     * The directory path of the configure
     *
     * @param string $path
     * @return string
     */
    public function configDir(string $path = ''): string {
        return $this->applicationDir('config') . ($path ? "/{$path}" : '');
    }

    /**
     * The directory path of the locale files
     *
     * @param string $path
     * @return string
     */
    public function localeDir(string $path = ''): string {
        return $this->configDir('locale') . ($path ? "/{$path}" : '');
    }

    /**
     * The directory of the modules
     *
     * @param string $path
     * @return string
     */
    public function moduleDir(string $path = ''): string {
        return $this->applicationDir('module') . ($path ? "/{$path}" : '');
    }

    /**
     * The directory path of the storage
     *
     * @param string $path
     * @return string
     */
    public function storageDir(string $path = ''): string {
        return $this->rootDir('storage') . ($path ? "/{$path}" : '');
    }

    /**
     * The directory path of storage
     *
     * @param string $path
     * @return string
     */
    public function cacheDir(string $path = ''): string {
        return $this->storageDir('cache') . ($path ? "/{$path}" : '');
    }

    /**
     * The directory path of the storage to cache config
     *
     * @param string $path
     * @return string
     */
    public function configCacheDir(string $path = ''): string {
        return $this->cacheDir('config') . ($path ? "/{$path}" : '');
    }


    /**
     * The directory path of the storage to cache compiled volt
     *
     * @param string $path
     * @return string
     */
    public function voltCacheDir(string $path = ''): string {
        return $this->cacheDir('volt') . ($path ? "/{$path}" : '');
    }

    /**
     * The directory path of the storage to cache logs file
     *
     * @param string $path
     * @return string
     */
    public function logDir(string $path = ''): string {
        return $this->storageDir('log') . ($path ? "/{$path}" : '');
    }

}
