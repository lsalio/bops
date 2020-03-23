<?php
/**
 * This file is part of bops
 *
 * @copyright Copyright (C) 2020 Jayson Wang
 * @license   MIT License
 * @link      https://github.com/wjiec/php-bops
 */
namespace Bops\Mvc;

use Bops\Database\Pool;
use Bops\Exception\Framework\Mvc\Model\ModelSaveException;
use Phalcon\Mvc\Model as MvcModel;
use Phalcon\Mvc\Model\ResultsetInterface;
use Throwable;


/**
 * Class Model
 *
 * @package Bops\Mvc
 */
abstract class Model extends MvcModel {

    /**
     * Sets up the model
     */
    public function initialize() {
        /* @var Pool $pool */
        $pool = container('db.pool');
        if (!empty($pool->getWriters())) {
            $this->setWriteConnectionService("db.writer.{$pool->getRandomWriter()}");
        }
        if (!empty($pool->getReaders())) {
            $this->setReadConnectionService("db.reader.{$pool->getRandomReader()}");
        }
    }

    /**
     * Returns the save and refresh succeed both
     *
     * @param null $data
     * @param null $whiteList
     * @return bool
     * @throws ModelSaveException
     */
    public function save($data = null, $whiteList = null) {
        try {
            if (parent::save($data, $whiteList) && $this->refresh()) {
                return true;
            }
            throw new ModelSaveException(join('|', $this->getMessages()));
        } catch (Throwable $e) {
            throw new ModelSaveException($e->getTraceAsString());
        }
    }

    /**
     * Allows to query a set of records that match the specified conditions
     *
     * @param mixed $parameters
     * @return ResultsetInterface
     */
    public static function find($parameters = null) {
        return parent::find($parameters);
    }

    /**
     * Allows to query the first record that match the specified conditions
     *
     * @param mixed $parameters
     * @return MvcModel|$this|null
     */
    public static function findFirst($parameters = null) {
        return parent::findFirst($parameters) ?: null;
    }

    /**
     * Execute the dql statement and returns number of affected rows
     *
     * @param string $sql
     * @param mixed ...$args
     * @return int
     */
    protected function dml(string $sql, ...$args): int {
        $db = container('db');

        $db->execute($this->prepareSql($sql), $args);
        return $db->affectedRows();
    }

    /**
     * Returns string of the sql replaced
     *
     * @param string $sql
     * @return string
     */
    private function prepareSql(string $sql): string {
        return mb_ereg_replace('__table__', $this->getSource(), $sql);
    }

    /**
     * Boolean value true
     */
    public const BOOLEAN_TRUE = 1;

    /**
     * Boolean value false
     */
    public const BOOLEAN_FALSE = 0;

}
