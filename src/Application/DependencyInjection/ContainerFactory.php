<?php
/**
 * PHP version 7.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace Ads\Application\DependencyInjection;

use Ads\Application\DependencyInjection\Extensions\ApplicationExtension;
use Ads\Application\DependencyInjection\Extensions\SlimExtension;
use Psr\Container\ContainerInterface;
use Slim\CallableResolver;
use Slim\Http\Environment;
use Slim\Http\Request;
use Symfony\Component\Config\ConfigCache;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Dumper\PhpDumper;

class ContainerFactory
{
    /** @var ContainerInterface */
    private static $container;

    /** @throws \Exception */
    public static function new(): ContainerInterface
    {
        if (!self::$container) {
            self::initializeContainer();
        }
        return self::$container;
    }

    private static function initializeContainer(): void
    {
        $appEnv = $_ENV['APP_ENV'] ?? 'dev';
        $appDebug = $_ENV['APP_DEBUG'] === '1';
        $file = dirname(__DIR__, 3) . "/var/container-{$appEnv}.php";
        $cache = new ConfigCache($file, $appDebug);
        if (!$cache->isFresh()) {
            self::buildContainer($cache, $appEnv, $appDebug);
        }
        require_once $file;
        $container = new ApplicationContainer();
        $environment = new Environment($_SERVER);
        $container->set('environment', $environment);
        $container->set('request', Request::createFromEnvironment($environment));
        $container->set('callableResolver', new CallableResolver($container));
        self::$container = $container;
    }

    private static function buildContainer(ConfigCache $cache, string $appEnv, bool $appDebug): void
    {
        $builder = new ContainerBuilder();
        $builder->setParameter('app.debug', $appDebug);
        $builder->setParameter('app.basePath', dirname(__DIR__, 3) . '/');
        $builder->setParameter('jwt.secret', $_ENV['JWT_SECRET']);

        $slimExtension = new SlimExtension();
        $applicationExtension = new ApplicationExtension($appEnv);
        $builder->registerExtension($slimExtension);
        $builder->registerExtension($applicationExtension);
        $builder->loadFromExtension($slimExtension->getAlias());
        $builder->loadFromExtension($applicationExtension->getAlias());

        $builder->compile();

        $cache->write(
            (new PhpDumper($builder))->dump([
                'class' => 'ApplicationContainer',
                'namespace' => 'Ads\\Application\\DependencyInjection'
            ]),
            $builder->getResources()
        );
    }
}
