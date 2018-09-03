<?php
/**
 * PHP version 7.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace Ads\Application\DependencyInjection\Extensions;

use Slim\Collection;
use Slim\Handlers\NotAllowed;
use Slim\Handlers\NotFound;
use Slim\Handlers\Strategies\RequestResponse;
use Slim\Http\Headers;
use Slim\Http\Response;
use Slim\Router;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\ExtensionInterface;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;

class SlimExtension implements ExtensionInterface, CompilerPassInterface
{
    /** @throws \Exception If configuration file cannot be loaded */
    public function load(array $configs, ContainerBuilder $builder): void
    {
        $loader = new YamlFileLoader($builder, new FileLocator(__DIR__ . '/../Resources'));
        $loader->load('slim.yml');
    }

    /**
     * Returns the namespace to be used for this extension (XML namespace).
     */
    public function getNamespace(): string
    {
        return '';
    }

    /**
     * Returns the base path for the XSD files.
     */
    public function getXsdValidationBasePath(): string
    {
        return '';
    }

    /**
     * Returns the recommended alias to use in XML.
     *
     * This alias is also the mandatory prefix to use when using YAML.
     */
    public function getAlias(): string
    {
        return 'slim';
    }

    /**
     * You can modify the container here before it is dumped to PHP code.
     */
    public function process(ContainerBuilder $builder)
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
