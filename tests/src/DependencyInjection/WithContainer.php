<?php
/**
 * PHP version 7.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace Ads\DependencyInjection;

use Ads\Application\DependencyInjection\ContainerFactory;
use Psr\Container\ContainerInterface;

trait WithContainer
{
    public function container(): ContainerInterface
    {
        return ContainerFactory::new();
    }
}
