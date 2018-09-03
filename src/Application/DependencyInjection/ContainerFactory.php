<?php
/**
 * PHP version 7.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace Ads\Application\DependencyInjection;

use Psr\Container\ContainerInterface;
use Slim\CallableResolver;
use Slim\Collection;
use Slim\Handlers\NotAllowed;
use Slim\Handlers\NotFound;
use Slim\Handlers\Strategies\RequestResponse;
use Slim\Http\Environment;
use Slim\Http\Headers;
use Slim\Http\Request;
use Slim\Http\Response;
use Slim\Router;
use Symfony\Component\Config\ConfigCache;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Dumper\PhpDumper;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;

class ContainerFactory
{
    /** @var ContainerInterface */
    private static $container;

    /** @throws \Exception */
    public static function new(): ContainerInterface
    {
        if (!self::$container) {
            $file = __DIR__ . '/../../../var/container.php';
            $cache = new ConfigCache($file, true);
            if (!$cache->isFresh()) {
                self::buildContainer($cache);
            }
            require_once $file;
            $container = new ApplicationContainer();
            $environment = new Environment($_SERVER);
            $container->set('environment', $environment);
            $container->set('request', Request::createFromEnvironment($environment));
            $container->set('callableResolver', new CallableResolver($container));
            self::$container = $container;
        }

        return self::$container;
    }

    /** @throws \Exception */
    private static function buildContainer(ConfigCache $cache): void
    {
        $builder = new ContainerBuilder();
        self::registerDefaultServices($builder);

        $loader = new YamlFileLoader($builder, new FileLocator(__DIR__ . '/../../../config'));
        $loader->load('services.yml');

        $builder->setParameter('app.debug', $_ENV['APP_DEBUG'] === 'true');
        $builder->setParameter('app.basePath', __DIR__ . '/../../../');
        $builder->setParameter('jwt.secret', $_ENV['JWT_SECRET']);

        $builder->compile();
        $dumper = new PhpDumper($builder);
        $cache->write(
            $dumper->dump([
                'class' => 'ApplicationContainer',
                'namespace' => 'Ads\\Application\\DependencyInjection'
            ]),
            $builder->getResources()
        );
    }

    private static function registerDefaultServices(ContainerBuilder $builder): void
    {
        $builder->register('settings', Collection::class)
            ->addArgument([
                'httpVersion' => '%slim.config.httpVersion%',
                'responseChunkSize' => '%slim.config.response.chunkSize%',
                'outputBuffering' => '%slim.config.outputBuffering%',
                'determineRouteBeforeAppMiddleware' => '%slim.config.determineRouteBeforeAppMiddleware%',
                'displayErrorDetails' => '%slim.config.displayErrorDetails%',
            ])
            ->setPublic(true);
        $headers = $builder->register(Headers::class, Headers::class)
            ->addArgument(['Content-Type' => '%slim.config.response.defaultContentType%']);
        $builder->register('response', Response::class)
            ->addArgument(200)
            ->addArgument($headers)
            ->addMethodCall('withProtocolVersion', ['%slim.config.httpVersion%'])
            ->setPublic(true);
        $builder->register('router', Router::class)->setPublic(true);
        $builder->register('foundHandler', RequestResponse::class)->setPublic(true);
        $builder->register('notFoundHandler', NotFound::class)->setPublic(true);
        $builder->register('notAllowedHandler', NotAllowed::class)->setPublic(true);
    }
}
