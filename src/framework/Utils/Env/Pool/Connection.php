<?php
/**
 * This file is part of bops
 *
 * @copyright Copyright (C) 2020 Jayson Wang
 * @license   MIT License
 * @link      https://github.com/wjiec/php-bops
 */
namespace Bops\Utils\Env\Pool;

use function Xet\str_has_prefix;


/**
 * Class Connection
 *
 * @package Bops\Utils\Env\Pool
 */
abstract class Connection {

    /**
     * The name of the primary connection
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
     * The configure of connection
     *
     * @var array[]
     */
    protected $configures;

    /**
     * The instance of connections
     *
     * @var array
     */
    protected $connections;

    /**
     * Connection constructor.
     */
    public function __construct() {
        $this->primary = null;
        $this->writers = [];
        $this->readers = [];
        $this->connections = [];
    }

    /**
     * Set primary into pool
     *
     * @param string $primary
     * @return $this
     */
    public function setPrimary(string $primary) {
        if ($this->loadConfig($primary)) {
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
            return $this->getConnection($this->primary);
        }

        if (empty($this->writers) && empty($this->readers)) {
            return null;
        }

        $intersect = array_intersect(array_keys($this->readers), array_keys($this->writers));
        if (empty($intersect)) {
            if (!empty($this->writers)) {
                return $this->getConnection($this->getRandomWriter());
            }
            return $this->getConnection($this->getRandomReader());
        }
        return $this->getConnection($intersect[array_rand($intersect)]);
    }

    /**
     * Set writers into connections
     *
     * @param array $writers
     * @return $this
     */
    public function setWriters(array $writers) {
        foreach ($writers as $writer) {
            if ($this->loadConfig($writer)) {
                $this->writers[] = $writer;
            }
        }
        return $this;
    }

    /**
     * Returns name of the writer connection by random
     *
     * @return string|null
     */
    public function getRandomWriter(): ?string {
        if (empty($this->writers)) {
            return null;
        }
        return $this->writers[array_rand($this->writers)];
    }

    /**
     * Returns list of writer name
     *
     * @return array
     */
    public function getWriters(): array {
        return $this->writers;
    }

    /**
     * Returns the writer connection
     *
     * @param string $name
     * @return mixed|null
     */
    public function getWriter(string $name) {
        if (!in_array($name, $this->writers)) {
            return null;
        }
        return $this->getConnection($name);
    }

    /**
     * Set readers into connections
     *
     * @param array $readers
     * @return $this
     */
    public function setReaders(array $readers) {
        foreach ($readers as $reader) {
            if ($this->loadConfig($reader)) {
                $this->readers[] = $reader;
            }
        }
        return $this;
    }

    /**
     * Returns name of the reader connection by random
     *
     * @return string|null
     */
    public function getRandomReader(): ?string {
        if (empty($this->readers)) {
            return null;
        }
        return $this->readers[array_rand($this->readers)];
    }

    /**
     * Returns list of reader name
     *
     * @return array
     */
    public function getReaders(): array {
        return $this->readers;
    }

    /**
     * Returns the reader connection
     *
     * @param string $name
     * @return mixed|null
     */
    public function getReader(string $name) {
        if (!in_array($name, $this->readers)) {
            return null;
        }
        return $this->getConnection($name);
    }

    /**
     * @param string $name
     * @return array
     */
    protected function loadConfig(string $name): array {
        if (!isset($this->configures[$name])) {
            $prefix = $this->getPrefix($name);
            $keys = array_filter(array_keys($_SERVER), function(string $key) use ($prefix) {
                return str_has_prefix($key, $prefix);
            });

            $values = array_map(function(string $key) { return $_SERVER[$key]; }, $keys);
            $keys = array_map(function(string $key) use ($prefix) {
                return strtolower(substr($key, strlen($prefix)));
            }, $keys);

            $this->configures[$name] = array_combine($keys, $values);
        }
        return $this->configures[$name];
    }

    /**
     * Get a shared connection from pool
     *
     * @param string $name
     * @return mixed
     */
    protected function getConnection(string $name) {
        if (!isset($this->connections[$name])) {
            $this->connections[$name] = $this->makeConnection($this->configures[$name]);
        }
        return $this->connections[$name];
    }

    /**
     * Returns the prefix string for mapping
     *
     * @param string $name
     * @return string
     */
    abstract protected function getPrefix(string $name): string;

    /**
     * Make a connection
     *
     * @param array $config
     * @return mixed
     */
    abstract protected function makeConnection(array $config);

}
