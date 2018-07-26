<?php
/**
 * PHP version 7.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace Ads\Ports\Web\Slim\DependencyInjection;

use Ads\Ports\Doctrine\EntityManagerFactory;
use Ads\Ports\Doctrine\Posters\PosterRepository;
use Ads\Ports\Web\Slim\Controllers\SignUpPosterController;
use Ads\Posters\Posters;
use Ads\Registration\SignUp\SignUpPoster;
use Ads\Registration\SignUp\SignUpPosterAction;
use Doctrine\ORM\EntityManager;
use Pimple\Container;
use Pimple\ServiceProviderInterface;

class ApplicationServices implements ServiceProviderInterface
{
    use EntityManagerFactory;

    /** @var array */
    private $options;

    public function __construct(array $options)
    {
        $this->options = $options;
    }

    public function register(Container $container): void
    {
        $container[EntityManager::class] = function () {
            return $this->entityManager($this->options);
        };
        $container[Posters::class] = function (Container $container) {
            return new PosterRepository($container[EntityManager::class]);
        };
        $container[SignUpPosterController::class] = function (Container $container) {
            return new SignUpPosterController(
                new SignUpPosterAction(new SignUpPoster($container[Posters::class])),
                $container['router']
            );
        };
    }
}
