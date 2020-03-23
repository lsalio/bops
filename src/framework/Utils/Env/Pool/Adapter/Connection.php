<?php
/**
 * This file is part of bops
 *
 * @copyright Copyright (C) 2020 Jayson Wang
 * @license   MIT License
 * @link      https://github.com/wjiec/php-bops
 */
namespace Bops\Utils\Env\Pool\Adapter;

use Bops\Utils\Env\Pool\AbstractPool;


/**
 * Class Connection
 *
 * @package Bops\Utils\Env\Pool\Adapter
 */
abstract class Connection extends AbstractPool {

    /**
     * The name of the primary database
     *
     * @var string
     */
    protected $primary;

    /**
     * The name of the writers
     *
     * @var string[] $writers
     */
    protected $writers;

    /**
     * The name of the readers
     *
     * @var string[]
     */
    protected $readers;

    /**
     * Pool constructor.
     *
     */
    public function __construct() {
        $this->primary = '';
        $this->writers = [];
        $this->readers = [];
    }

    /**
     * Set primary into pool
     *
     * @param string $primary
     * @return $this
     */
    public function setPrimary(string $primary) {
        if ($this->makeConnection($primary)) {
            $this->primary = $primary;
        }

        return $this;
    }

    /**
     * Returns the primary connection
     *
     * @return mixed
     */
    public function getPrimary() {
        if ($this->primary) {
            return $this->primary;
        }

        $intersect = array_intersect(array_keys($this->readers), array_keys($this->writers));
        if (empty($intersect)) {
            return null;
        }
        return $this->writers[array_rand($intersect)];
    }

    /**
     * Set writers into connections
     *
     * @param array $writers
     * @return $this
     */
    public function setWriters(array $writers) {
        foreach ($writers as $writer) {
            if ($connection = $this->makeConnection($writer)) {
                $this->writers[] = $connection;
            }
        }

        return $this;
    }

    /**
     * Set readers into connections
     *
     * @param array $readers
     * @return $this
     */
    public function setReaders(array $readers) {
        foreach ($readers as $reader) {
            if ($connection = $this->makeConnection($reader)) {
                $this->readers[] = $connection;
            }
        }

        return $this;
    }

    /**
     * Make connection from name
     *
     * @param string $name
     * @return mixed
     */
    protected function makeConnection(string $name) {
        $connection = $this->doMakeConnection(self::map($this->getPrefix($name)));
        $this->add($name, $connection);

        return $connection;
    }

    /**
     * Returns the prefix string for mapping
     *
     * @param string $name
     * @return string
     */
    abstract protected function getPrefix(string $name): string;

    /**
     * Make a connection and append into pool
     *
     * @param array $config
     * @return mixed
     */
    abstract protected function doMakeConnection(array $config);

}
