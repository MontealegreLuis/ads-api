<?php
/**
 * PHP version 7.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace Ads\Ports\Web\Slim;

use Ads\Ports\Web\Slim\Controllers\SignUpPosterController;
use Slim\App;

class Application extends App
{
    public function __construct($container = [])
    {
        parent::__construct($container);
        $this->post('/sign-up', SignUpPosterController::class . ':signUp');
    }
}
