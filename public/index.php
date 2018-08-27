<?php
/**
 * PHP version 7.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

require __DIR__ . '/../vendor/autoload.php';

use Ads\Application\DependencyInjection\ContainerFactory;
use Ads\UI\Web\Slim\Application;
use Dotenv\Dotenv;

$env = new Dotenv(__DIR__ . '/../');
$env->load();

(new Application(ContainerFactory::new()))->run();
