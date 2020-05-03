<?php
/**
 * This file is part of bops
 *
 * @copyright Copyright (C) 2020 Jayson Wang
 * @license   MIT License
 * @link      https://github.com/wjiec/php-bops
 */
namespace Bops\Http\Uri;

use Bops\Exception\Framework\Application\ModuleNameReservedException;
use Phalcon\Mvc\Dispatcher;
use Throwable;
use function Xet\array_any;


/**
 * Class Version
 *
 * @package Bops\Http\Uri
 */
class Version {

    /**
     * The modules config
     *
     * @var array
     */
    protected $modules;

    /**
     * The states of modules supports
     *
     * @var array
     */
    protected $states;

    /**
     * The number of the version
     *
     * @var int
     */
    protected $number = 1;

    /**
     * Version constructor.
     *
     * @throws ModuleNameReservedException
     */
    public function __construct() {
        $this->states = array();

        $this->modules = container('modules')->toArray();
        foreach ($this->modules as $name => $module) {
            $this->states[$name] = false;
            if (isset($module['metadata']) && is_array($module['metadata'])) {
                $this->states[$name] = $module['metadata']['versionUri'] ?? false;
            }
        }

        if (array_any($this->states)) {
            foreach (array_keys($this->modules) as $name) {
                if (preg_match('/^v\d+$/', $name)) {
                    throw new ModuleNameReservedException("The name of the module \"{$name}\" has reversed");
                }
            }
        }
    }

    /**
     * Returns the version-feature is enabled for module, default by
     * module not found in modules config
     *
     * @param string $module
     * @param bool $default
     * @return bool
     */
    public function check(?string $module = null, bool $default = false): bool {
        if (!$module) {
            return array_any($this->states);
        }
        return $this->states[$module] ?? $default;
    }

    /**
     * Returns the version-feature is enabled for module by strict, default by
     * module not found in modules config
     *
     * @param string|null $module
     * @param bool $default
     * @return bool
     */
    public function strictCheck(string $module = null, bool $default = false): bool {
        return $this->states[$module] ?? $default;
    }

    /**
     * Sets the number of version
     *
     * @param int $version
     */
    public function setNumber(int $version): void {
        $this->number = abs($version);
    }

    /**
     * Gets the number of version
     *
     * @return int
     */
    public function getNumber(): int {
        return $this->number;
    }

    /**
     * Returns the namespace with version suffix when feature enabled
     *
     * @param string $module
     * @param string $namespace
     * @return string
     */
    public function namespaceOf(string $module, string $namespace): string {
        if ($this->check($module)) {
            return sprintf('%s\V%d', rtrim($namespace, '\\'), $this->getNumber());
        }
        return $namespace;
    }

    /**
     * Returns a namespace of controller on version fallback
     *
     * @param string $namespace
     * @return string
     */
    public function namespaceFallback(string $namespace): string {
        return preg_replace('/(.*)(\\\V\d+)$/', '$1', $namespace);
    }

    /**
     * Returns true when the exception is handler cannot be loaded
     *
     * @param Throwable $exception
     * @return bool
     */
    public function isVersionHandlerException(Throwable $exception): bool {
        if ($exception->getCode() === Dispatcher::EXCEPTION_HANDLER_NOT_FOUND) {
            return strpos($exception->getMessage(), "\\V{$this->number}\\") !== false;
        }
        return false;
    }

}
