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
 * Class Html
 *
 * @package Bops\Mvc\View\Adapter
 */
class Html extends AbstractView {

    /**
     * Load config
     *
     * @param array $config
     * @return $this
     */
    public function loadConfig(array $config) {
        if (!empty($config['viewDir'])) {
            $this->setViewsDir($config['viewDir']);
        }

        return $this;
    }

}
