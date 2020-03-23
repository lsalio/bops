<?php
/**
 * This file is part of bops
 *
 * @copyright Copyright (C) 2020 Jayson Wang
 * @license   MIT License
 * @link      https://github.com/wjiec/php-bops
 */
namespace Bops\Cache;

use Bops\Utils\Env\Pool\Connection;
use Phalcon\Cache\Backend\Factory;
use Phalcon\Cache\Frontend\Factory as FrontendFactory;
use function Xet\array_at;


/**
 * Class Pool
 *
 * @package Bops\Cache
 */
class Pool extends Connection {

    /**
     * Returns the prefix string for mapping
     *
     * @param string $name
     * @return string
     */
    protected function getPrefix(string $name): string {
        return sprintf('SERVICE_CACHE_%s_', strtoupper($name));
    }

    /**
     * Make a connection
     *
     * @param array $config
     * @return mixed
     */
    protected function makeConnection(array $config) {
        $frontend = [
            'adapter' => array_at($config, 'frontend', 'data'),
            'lifetime' => array_at($config, 'lifetime', 86400)
        ];
        $config['frontend'] = FrontendFactory::load($frontend);

        unset($config['lifetime']);
        return Factory::load($config);
    }

}
