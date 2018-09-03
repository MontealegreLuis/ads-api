<?php
/**
 * PHP version 7.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace Ads\Application\DependencyInjection\Extensions;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\ExtensionInterface;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;

class ApplicationExtension implements ExtensionInterface
{
    /** @var string */
    private $appEnv;

    public function __construct(string $appEnv)
    {
        $this->appEnv = $appEnv;
    }

    /** @throws \Exception If configuration file cannot be loaded */
    public function load(array $configs, ContainerBuilder $builder): void
    {
        $loader = new YamlFileLoader($builder, new FileLocator(__DIR__ . '/../Resources'));
        $loader->load("application.{$this->appEnv}.yml");
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
        return 'application';
    }
}
