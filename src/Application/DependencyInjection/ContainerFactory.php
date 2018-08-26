<?php
/**
 * PHP version 7.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace Ads\Application\DependencyInjection;

use Slim\Container;

class ContainerFactory
{
    /** @var Container */
    private static $container;

    public static function new(): Container
    {
        if (!self::$container) {
            self::$container = new Container();
            self::$container->register(new ApplicationServices(require __DIR__ . '/../../../config/options.php'));
        }

        return self::$container;
    }
}
