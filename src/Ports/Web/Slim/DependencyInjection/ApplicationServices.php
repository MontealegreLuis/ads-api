<?php
/**
 * PHP version 7.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace Ads\Ports\Web\Slim\DependencyInjection;

use Ads\Ports\Web\Slim\Controllers\SignUpPosterController;
use Ads\Posters\InMemoryPosters;
use Ads\Registration\SignUp\SignUpPoster;
use Ads\Registration\SignUp\SignUpPosterAction;
use Slim\Container;

class ApplicationServices
{
    public function register(Container $container): void
    {
        $container[SignUpPosterController::class] = function (Container $container) {
            return new SignUpPosterController(
                new SignUpPosterAction(new SignUpPoster(new InMemoryPosters())),
                $container['router']
            );
        };
    }
}
