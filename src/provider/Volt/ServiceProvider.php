<?php
/**
 * This file is part of bops
 *
 * @copyright Copyright (C) 2020 Jayson Wang
 * @license   MIT License
 * @link      https://github.com/wjiec/php-bops
 */
namespace Bops\Provider\Volt;

use Bops\Environment\Environment;
use Bops\Mvc\View\Engine\Volt\Extension\ExtensionInterface;
use Bops\Navigator\NavigatorInterface;
use Bops\Provider\AbstractServiceProvider;
use Phalcon\Mvc\View\Engine\Volt;
use Phalcon\Mvc\ViewBaseInterface;


/**
 * Class ServiceProvider
 *
 * @package Bops\Provider\Volt
 */
class ServiceProvider extends AbstractServiceProvider {

    /**
     * Name of the service
     *
     * @return string
     */
    public function name(): string {
        return 'volt';
    }

    /**
     * Register the service
     *
     * @return void
     */
    public function register() {
        $this->di->set($this->name(), function(ViewBaseInterface $view) {
            $volt = new Volt($view, container());
            /* @var NavigatorInterface $navigator */
            $navigator = container('navigator');

            /* @see Environment::contains() */
            $isDevelopment = container('environment')->contains('development');
            $volt->setOptions([
                'compileAlways' => $isDevelopment || env('BOPS_DEBUG'),
                'compiledPath' => function(string $path) use ($navigator) {
                    $relativePath = trim(mb_substr($path, mb_strlen($navigator->rootDir())), '\\/');
                    $basename = basename(str_replace(['\\', '/'], '_', $relativePath), '.volt');

                    $cacheDir = $navigator->voltCacheDir();
                    if (!is_dir($cacheDir)) {
                        @mkdir($cacheDir, 0755, true);
                    }

                    return $cacheDir . DIRECTORY_SEPARATOR . $basename . '.php';
                }
            ]);

            if ($extension = container('volt.extension')) {
                if ($extension instanceof ExtensionInterface) {
                    $volt->getCompiler()->addExtension($extension->inject($volt->getCompiler()));
                }
            }

            return $volt;
        });
    }

}
