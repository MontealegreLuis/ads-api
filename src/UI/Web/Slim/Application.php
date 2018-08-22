<?php
/**
 * PHP version 7.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace Ads\UI\Web\Slim;

use Ads\Application\DependencyInjection\ApplicationServices;
use Ads\UI\Web\Slim\Controllers\SignUpPosterController;
use Ads\UI\Web\Slim\Middleware\EventSubscribersMiddleware;
use Ads\UI\Web\Slim\Middleware\QueryLoggerMiddleware;
use Ads\UI\Web\Slim\Middleware\RequestLoggerMiddleware;
use Slim\App;

class Application extends App
{
    public function __construct($options = [])
    {
        parent::__construct($options);
        $container = $this->getContainer();
        (new ApplicationServices($options))->register($container);

        $this->add($container[RequestLoggerMiddleware::class]);
        $this->add($container[EventSubscribersMiddleware::class]);
        $this->add($container[QueryLoggerMiddleware::class]);

        $this->post('/posters', SignUpPosterController::class . ':signUp')
            ->setName('signUp');
        $this->get('/posters/{username}', SignUpPosterController::class . ':signUp')
            ->setName('poster');
    }
}
