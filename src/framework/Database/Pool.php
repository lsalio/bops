<?php
/**
 * This file is part of bops
 *
 * @copyright Copyright (C) 2020 Jayson Wang
 * @license   MIT License
 * @link      https://github.com/wjiec/php-bops
 */
namespace Bops\Database;

use Bops\Listener\ListenerInterface;
use Bops\Utils\Env\Pool\Connection;
use Phalcon\Db\Adapter\Pdo\Factory as PdoFactory;


/**
 * Class Pool
 *
 * @package Bops\Database
 */
class Pool extends Connection {

    /**
     * Returns the prefix string for mapping
     *
     * @param string $name
     * @return string
     */
    protected function getPrefix(string $name): string {
        return sprintf('SERVICE_DATABASE_%s_', strtoupper($name));
    }

    /**
     * Make a connection
     *
     * @param array $config
     * @return mixed
     */
    protected function makeConnection(array $config) {
        $connection = PdoFactory::load($config);

        $connection->setEventsManager(container('eventsManager'));
        if ($listener = container('db.listener')) {
            if ($listener instanceof ListenerInterface) {
                /* @see Manager::listen() */
                container('eventsManager')->listen('db', $listener);
            }
        }

        return $connection;
    }

}
