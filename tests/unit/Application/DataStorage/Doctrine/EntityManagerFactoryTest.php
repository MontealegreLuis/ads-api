<?php
/**
 * PHP version 7.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace Ads\Application\DataStorage;

use Ads\DependencyInjection\WithContainer;
use PHPUnit\Framework\TestCase;
use Symfony\Component\DependencyInjection\Container;

class EntityManagerFactoryTest extends TestCase
{
    use WithContainer;

    /** @test */
    function it_gets_singleton_instance_of_entity_manager()
    {
        /** @var Container $container */
        $container = $this->container();
        $options = $container->getParameter('em.options');
        $otherOptions = $options;
        $otherOptions['debug'] = false;
        $this->assertSame(
            EntityManagerFactory::new($options),
            EntityManagerFactory::new($otherOptions)
        );
    }

    /** @test */
    function it_gets_multiple_instances_of_entity_manager()
    {
        /** @var Container $container */
        $container = $this->container();
        $options = $container->getParameter('em.options');
        $otherOptions = $options;
        $otherOptions['debug'] = false;
        $this->assertNotSame(
            EntityManagerFactory::create($options),
            EntityManagerFactory::create($otherOptions)
        );
    }
}
