<?php
/**
 * This file is part of bops
 *
 * @copyright Copyright (C) 2020 Jayson Wang
 * @license   MIT License
 * @link      https://github.com/wjiec/php-bops
 */

namespace Bops\Utils\Env\Connection;
use Phalcon\DiInterface;


/**
 * Class Installer
 *
 * @package Bops\Utils\Env\Connection
 */
class Installer {

    /**
     * Installs all connection into di
     *
     * @param string $service
     * @param Pool $pool
     * @param DiInterface $di
     */
    public static function register(string $service, Pool $pool, DiInterface $di) {
        $di->setShared($service, function() use ($pool) {
            return $pool->getPrimary();
        });

        $di->setShared("{$service}.pool", $pool);
        foreach ($pool->getWriters() as $writer) {
            $di->setShared("{$service}.writer.{$writer}", function () use ($pool, $writer) {
                return $pool->getWriter($writer);
            });
        }
        foreach ($pool->getReaders() as $reader) {
            $di->setShared("{$service}.reader.{$reader}", function () use ($pool, $reader) {
                return $pool->getReader($reader);
            });
        }
    }


}
