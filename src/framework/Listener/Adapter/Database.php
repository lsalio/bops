<?php
/**
 * This file is part of bops
 *
 * @copyright Copyright (C) 2020 Jayson Wang
 * @license   MIT License
 * @link      https://github.com/wjiec/php-bops
 */
namespace Bops\Listener\Adapter;

use Bops\Listener\AbstractListener;
use Phalcon\Db\Adapter\Pdo;
use Phalcon\Events\Event;


/**
 * Class Database
 *
 * @package Bops\Listener\Adapter
 */
class Database extends AbstractListener {

    /**
     * Database queries listener
     *
     * @param Event $event
     * @param Pdo $connection
     * @return bool
     */
    public function beforeQuery(Event $event, Pdo $connection): bool {
        if (env('BOPS_DEBUG') || container('environment')->contains('development')) {
            $statement = $connection->getSQLStatement();
            $variables = $connection->getSqlVariables();

            $logger = container('logger', 'db');
            $logger->debug(sprintf("Database beforeQuery:\nStatement: %s\nVariables:%s\n",
                $statement, join(', ', ($variables ?? []))
            ));
        }

        return true;
    }

}
