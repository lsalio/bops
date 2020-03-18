<?php
/**
 * This file is part of bops
 *
 * @copyright Copyright (C) 2020 Jayson Wang
 * @license   MIT License
 * @link      https://github.com/wjiec/php-bops
 */
namespace Bops\Provider;

use Bops\Exception\Provider\EmptyServiceNameException;
use Phalcon\DiInterface;
use Phalcon\Mvc\User\Component;


/**
 * Class AbstractServiceProvider
 *
 * @package Bops\Provider
 */
abstract class AbstractServiceProvider extends Component implements ServiceProviderInterface {

    /**
     * AbstractServiceProvider constructor.
     *
     * @param DiInterface $di
     * @throws EmptyServiceNameException
     */
    public function __construct(DiInterface $di) {
        if (empty($this->name())) {
            throw new EmptyServiceNameException(sprintf('The service provider "%s" cannot have an empty name',
                get_class($this)));
        }
        $this->setDI($di);
    }

    /**
     * Initializing the service
     *
     * @return void
     */
    public function initialize() {}

}
