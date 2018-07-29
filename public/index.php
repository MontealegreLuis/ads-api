<?php
/**
 * PHP version 7.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

use Ads\Ports\Web\Slim\Application;
use Ads\Ports\Web\Slim\DependencyInjection\ApplicationServices;
use Doctrine\Common\Annotations\AnnotationRegistry;

/** @var \Composer\Autoload\ClassLoader $loader */
$loader = require __DIR__ . '/../vendor/autoload.php';

AnnotationRegistry::registerLoader(Closure::fromCallable([$loader, 'loadClass']));

(new Application(new ApplicationServices(require __DIR__ . '/../config/options.php')))->run();
