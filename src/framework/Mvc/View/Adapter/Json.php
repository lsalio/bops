<?php
/**
 * This file is part of bops
 *
 * @copyright Copyright (C) 2020 Jayson Wang
 * @license   MIT License
 * @link      https://github.com/wjiec/php-bops
 */
namespace Bops\Mvc\View\Adapter;

use Bops\Exception\Framework\Mvc\View\JsonViewException;
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
     * Load config
     *
     * @param array $config
     * @return $this
     */
    public function loadConfig(array $config) {
        if (isset($config['jsonFlags'])) {
            $this->flags = $config['jsonFlags'];
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
    public function render($controllerName, $actionName, $params = null) {
        container('response')->setContentType('application/json; charset=utf-8');
    }

    /**
     * Build data and return it
     *
     * @return string|void
     * @throws JsonViewException
     */
    public function getContent() {
        if (empty($this->_viewParams)) {
            return '{}'; // bad style
        }

        $response = json_encode($this->_viewParams, $this->flags);
        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new JsonViewException(json_last_error_msg());
        }
        return $response;
    }

}
