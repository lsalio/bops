<?php
/**
 * This file is part of bops
 *
 * @copyright Copyright (C) 2020 Jayson Wang
 * @license   MIT License
 * @link      https://github.com/wjiec/php-bops
 */
namespace Bops\Provider;

use Bops\Di\Component;
use Bops\Exception\Provider\EmptyServiceNameException;


/**
 * Class AbstractServiceProvider
 *
 * @package Bops\Provider
 */
abstract class AbstractServiceProvider extends Component implements ServiceProviderInterface {

    /**
     * AbstractServiceProvider constructor.
     *
     * @throws EmptyServiceNameException
     */
    public function __construct() {
        parent::__construct();
        if (empty($this->name())) {
            throw new EmptyServiceNameException(sprintf('The service provider "%s" cannot have an empty name',
                get_class($this)));
        }
    }

    /**
     * Initializing the service
     *
     * @return void
     */
    public function initialize() {}

}
