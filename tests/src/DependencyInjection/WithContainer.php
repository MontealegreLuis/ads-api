<?php
/**
 * PHP version 7.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace Ads\DependencyInjection;

use Ads\Application\DependencyInjection\ContainerFactory;
use Pimple\Container;

trait WithContainer
{
    public function container(): Container
    {
        return ContainerFactory::new();
    }
}
