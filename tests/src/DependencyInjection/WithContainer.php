<?php
/**
 * PHP version 7.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace Ads\DependencyInjection;

use Ads\Ports\Web\Slim\DependencyInjection\ApplicationServices;
use Pimple\Container;

trait WithContainer
{
    public function container(): Container
    {
        $container = new Container();
        $container->register(new ApplicationServices(require __DIR__ . '/../../../config/options.php'));

        return $container;
    }
}
