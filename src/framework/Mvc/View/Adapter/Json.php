<?php
/**
 * This file is part of bops
 *
 * @copyright Copyright (C) 2020 Jayson Wang
 * @license   MIT License
 * @link      https://github.com/wjiec/php-bops
 */
namespace Bops\Mvc\View\Adapter;

use Bops\Mvc\View\AbstractView;


/**
 * Class Json
 *
 * @package Bops\Mvc\View\Adapter
 */
class Json extends AbstractView {

    /**
     * The flags to json_encode
     *
     * @var int|null
     */
    protected $flags = null;

    /**
     * response structure
     *
     * @var array
     */
    protected $format = array();

    /**
     * Load config
     *
     * @param array $config
     * @return $this
     */
    public function loadConfig(array $config) {
        if (isset($config['jsonFlags'])) {
            $this->flags = $config['jsonFlags'];
        }

        if (isset($config['jsonFormat']) && is_array($config['jsonFormat'])) {
            $this->format = $config['jsonFormat'];
        }

        return $this;
    }

    /**
     * Do nothing on json view
     *
     * @param string $controllerName
     * @param string $actionName
     * @param null $params
     */
    public function render($controllerName, $actionName, $params = null) {}

    /**
     * Build data and return it
     *
     * @return string|void
     */
    public function getContent() {
        $object = [];
        foreach ($this->format as $name => $key) {
            $object[$key] = $this->getVar($name);
        }
        return json_encode($object, $this->flags);
    }

}
