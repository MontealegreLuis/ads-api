<?php
/**
 * PHP version 7.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

use Ads\Application\DependencyInjection\ContainerFactory;
use Dotenv\Dotenv;

require __DIR__ . '/../vendor/autoload.php';

$env = new Dotenv(__DIR__ . '/../');
$env->load();

/** @var \Symfony\Component\DependencyInjection\Container $container */
$container = ContainerFactory::new();
$dbPath = $container->getParameter('db.connection.path');

if (file_exists($dbPath)) {
    unlink($dbPath);
}
