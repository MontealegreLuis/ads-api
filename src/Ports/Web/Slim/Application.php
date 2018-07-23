<?php
/**
 * PHP version 7.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace Ads\Ports\Web\Slim;

use Ads\Ports\Web\Slim\Controllers\SignUpPosterController;
use Ads\Ports\Web\Slim\DependencyInjection\ApplicationServices;
use Slim\App;

class Application extends App
{
    public function __construct(ApplicationServices $provider, $container = [])
    {
        parent::__construct($container);
        $provider->register($this->getContainer());

        $this->post('/sign-up', SignUpPosterController::class . ':signUp')
            ->setName('signUp');
        $this->get('/poster/{username}', SignUpPosterController::class . ':signUp')
            ->setName('poster');
    }
}
