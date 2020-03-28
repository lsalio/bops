<?php
/**
 * This file is part of bops
 *
 * @copyright Copyright (C) 2020 Jayson Wang
 * @license   MIT License
 * @link      https://github.com/wjiec/php-bops
 */
namespace Bops\Events;

use Bops\Listener\ListenerInterface;
use Phalcon\Events\Manager as EventsManager;


/**
 * Class Manager
 *
 * @package Bops\Events
 */
class Manager extends EventsManager {

    /**
     * Attach listener and detach all before
     *
     * @param string $type
     * @param ListenerInterface $listener
     */
    public function listen(string $type, ListenerInterface $listener) {
        $this->detachAll($type);
        $this->attach($type, $listener);
    }

}
