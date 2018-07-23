<?php
/**
 * PHP version 7.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

use Ads\Ports\Web\Slim\Application;

require __DIR__ . '/../vendor/autoload.php';

(new Application())->run();
