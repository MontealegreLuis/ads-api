<?php
/**
 * PHP version 7.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

require __DIR__ . '/../vendor/autoload.php';

use Ads\UI\Web\Slim\Application;

(new Application(require __DIR__ . '/../config/options.php'))->run();
