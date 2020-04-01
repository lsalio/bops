<?php
/**
 * This file is part of bops
 *
 * @noinspection PhpUnused
 *
 * @copyright Copyright (C) 2020 Jayson Wang
 * @license   MIT License
 * @link      https://github.com/wjiec/php-bops
 */
namespace Bops;

use Bops\Application\ApplicationInterface;
use Bops\Exception\Framework\Bootstrap\UnknownApplicationException;
use Bops\Listener\Adapter\Database as DatabaseListener;
use Bops\Listener\Adapter\Dispatcher as DispatcherListener;
use Bops\Listener\Adapter\Router as RouterListener;
use Bops\Listener\Adapter\View as ViewListener;
use Bops\Mvc\Application;
use Bops\Mvc\Dispatcher\Factory as DispatcherFactory;
use Bops\Mvc\View\Factory;
use Bops\Navigator\NavigatorInterface;
use Bops\Provider\Cache\ServiceProvider as CacheServiceProvider;
use Bops\Provider\Config\ServiceProvider as ConfigServiceProvider;
use Bops\Provider\Database\ServiceProvider as DatabaseServiceProvider;
use Bops\Provider\Environment\ServiceProvider as EnvironmentServiceProvider;
use Bops\Provider\ErrorHandler\ServiceProvider as ErrorHandlerServiceProvider;
use Bops\Provider\EventsManager\ServiceProvider as EventsManagerServiceProvider;
use Bops\Provider\Filesystem\ServiceProvider as FilesystemServiceProvider;
use Bops\Provider\Logger\ServiceProvider as LoggerServiceProvider;
use Bops\Provider\MiddlewareQueue\ServiceProvider as MiddlewareQueueServiceProvider;
use Bops\Provider\Router\ServiceProvider as RouterServiceProvider;
use Bops\Provider\ServiceProviderInstaller;
use Bops\Provider\Translator\ServiceProvider as TranslatorServiceProvider;
use Bops\Provider\Url\ServiceProvider as UrlServiceProvider;
use Bops\Provider\VersionUri\ServiceProvider as VersionUriServiceProvider;
use Bops\Provider\Volt\ServiceProvider as VoltServiceProvider;
use Dotenv\Dotenv;
use Dotenv\Exception\InvalidPathException;
use League\Flysystem\Filesystem;
use Phalcon\Di;
use Phalcon\Di\FactoryDefault;
use Phalcon\DiInterface;


/**
 * Class Bootstrap
 *
 * @package Bops
 */
class Bootstrap {

    /**
     * Dependency injection manager
     *
     * @var DiInterface
     */
    protected $di;

    /**
     * Bootstrap constructor.
     *
     * @noinspection PhpDocMissingThrowsInspection
     *
     * @param NavigatorInterface $navigator
     */
    public function __construct(NavigatorInterface $navigator) {
        $this->di = new FactoryDefault();
        Di::setDefault($this->di);

        $this->di->setShared('bootstrap', $this);
        $this->di->setShared('navigator', $navigator);

        /** @noinspection PhpUnhandledExceptionInspection */
        $this->setupEnvironment($navigator);
        ServiceProviderInstaller::setup(new ErrorHandlerServiceProvider());
        ServiceProviderInstaller::setup(new EventsManagerServiceProvider());
        /** @noinspection PhpUnhandledExceptionInspection */
        $this->setupServices($navigator);
    }

    /**
     * Run the application
     *
     * @param string $uri
     * @return string
     * @throws UnknownApplicationException
     */
    public function run(string $uri = ''): string {
        if ($application = container('application')) {
            if ($application instanceof ApplicationInterface) {
                /** @noinspection PhpUndefinedMethodInspection */
                return $application->handle($uri)->getContent();
            }
        }
        throw new UnknownApplicationException('The application service does not defined');
    }

    /**
     * Setup the application environment
     *
     * @param NavigatorInterface $navigator
     * @throws Exception\Provider\EmptyServiceNameException
     */
    protected function setupEnvironment(NavigatorInterface $navigator) {
        try {
            Dotenv::createMutable($navigator->rootDir())->load();
            if ($env = env('BOPS_ENVIRONMENT', 'development')) {
                ServiceProviderInstaller::setup(new EnvironmentServiceProvider());
                container('environment', $env);

                Dotenv::createMutable($navigator->rootDir(), [".env.{$env}"])->load();
            }
        } catch (InvalidPathException $e) {}
    }

    /**
     * Setup the service from users
     *
     * @param NavigatorInterface $navigator
     * @throws Exception\Provider\EmptyServiceNameException
     */
    protected function setupServices(NavigatorInterface $navigator) {
        $this->setupBuiltInServices();
        /* @var Filesystem $filesystem */
        if ($filesystem = container('filesystem', $navigator->configDir())) {
            if ($filesystem->has('providers.php')) {
                /** @noinspection PhpIncludeInspection */
                $providers = include $navigator->configDir('providers.php');
                if (is_array($providers) && !empty($providers)) {
                    $this->setupServiceProviders($providers);
                }
            }

            if ($filesystem->has('services.php')) {
                /** @noinspection PhpIncludeInspection */
                $services = include $navigator->configDir('services.php');
                if (is_array($services) && !empty($services)) {
                    $this->setRawServices($services);
                }
            }
        }
    }

    /**
     * Setup the built in services
     *
     * @throws Exception\Provider\EmptyServiceNameException
     */
    protected function setupBuiltInServices() {
        // generic services
        ServiceProviderInstaller::setup(new CacheServiceProvider());
        ServiceProviderInstaller::setup(new ConfigServiceProvider());
        ServiceProviderInstaller::setup(new DatabaseServiceProvider());
        ServiceProviderInstaller::setup(new FilesystemServiceProvider());
        ServiceProviderInstaller::setup(new LoggerServiceProvider());
        ServiceProviderInstaller::setup(new MiddlewareQueueServiceProvider());
        ServiceProviderInstaller::setup(new RouterServiceProvider());
        ServiceProviderInstaller::setup(new TranslatorServiceProvider());
        ServiceProviderInstaller::setup(new UrlServiceProvider());
        ServiceProviderInstaller::setup(new VersionUriServiceProvider());
        ServiceProviderInstaller::setup(new VoltServiceProvider());
        // listener services
        $this->di->setShared('dispatcher.listener', new DispatcherListener());
        $this->di->setShared('router.listener', new RouterListener());
        $this->di->setShared('view.listener', new ViewListener());
        $this->di->setShared('db.listener', new DatabaseListener());
        // defaults
        $this->di->setShared('application', new Application());
        $this->di->setShared('dispatcher', DispatcherFactory::factory(/* module */''));
        $this->di->setShared('view', Factory::html());
    }

    /**
     * Initializing the service providers
     *
     * @param array $providers
     */
    protected function setupServiceProviders(array $providers) {
        foreach ($providers as $provider) {
            ServiceProviderInstaller::setup(new $provider());
        }
    }

    /**
     * Initializing the raw services
     *
     * @param array $services
     */
    protected function setRawServices(array $services) {
        foreach ($services as $name => $service) {
            $this->di->setShared($name, $service);
        }
    }

}
