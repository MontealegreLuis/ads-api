<?php
/**
 * PHP version 7.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace Ads\Ports\Web\Slim\DependencyInjection;

use Ads\Ports\CommandBus\Bus;
use Ads\Ports\Doctrine\DomainEvents\EventStoreRepository;
use Ads\Ports\Doctrine\EntityManagerFactory;
use Ads\Ports\Doctrine\Posters\PosterRepository;
use Ads\Ports\DomainEvents\StoredEventFactory;
use Ads\Ports\DomainEvents\StoredEventsSubscriber;
use Ads\Ports\JmsSerializer\JSONSerializer;
use Ads\Ports\Web\Slim\Controllers\SignUpPosterController;
use Ads\Ports\Web\Slim\Middleware\EventSubscribersMiddleware;
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
        $container[EventStoreRepository::class] = function (Container $container) {
            return new EventStoreRepository($container[EntityManager::class]);
        };
        $container[SignUpPosterController::class] = function (Container $container) {
            return new SignUpPosterController(
                $container[Bus::class],
                new SignUpPosterAction(new SignUpPoster($container[Posters::class])),
                $container['router']
            );
        };
        $container[Bus::class] = function (Container $container) {
            return new Bus($container[EntityManager::class]);
        };
        $container[StoredEventsSubscriber::class] = function (Container $container) {
            return new StoredEventsSubscriber(
                $container[EventStoreRepository::class],
                new StoredEventFactory(new JSONSerializer())
            );
        };
        $container[EventSubscribersMiddleware::class] = function (Container $container) {
            return new EventSubscribersMiddleware($container[StoredEventsSubscriber::class]);
        };
    }
}
