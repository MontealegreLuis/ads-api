<?php
/**
 * PHP version 7.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

use Dotenv\Dotenv;

require __DIR__ . '/../vendor/autoload.php';

$env = new Dotenv(__DIR__ . '/../');
$env->load();

$options = require __DIR__ . '/../config/options.php';

$dbPath = $options['db']['connection']['path'];

if (file_exists($dbPath)) {
    unlink($dbPath);
}
