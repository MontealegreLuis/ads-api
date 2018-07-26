<?php
/**
 * PHP version 7.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

use Ads\Ports\Web\Slim\DependencyInjection\ApplicationServices;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Tools\Console\ConsoleRunner;
use Pimple\Container;

require_once __DIR__ . '/../vendor/autoload.php';

$container = new Container();
$container->register(new ApplicationServices(require __DIR__ . '/options.php'));

// need a username type since it is a primary key

return ConsoleRunner::createHelperSet($container[EntityManager::class]);
