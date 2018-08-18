<?php
/**
 * PHP version 7.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace Ads\Framework\DependencyInjection;

use Ads\Ports\CommandBus\Bus;
use Ads\Ports\Doctrine\DomainEvents\EventStoreRepository;
use Ads\Ports\Doctrine\EntityManagerFactory;
use Ads\Ports\Doctrine\Posters\PosterRepository;
use Ads\Ports\DomainEvents\StoredEventFactory;
use Ads\Ports\DomainEvents\StoredEventsSubscriber;
use Ads\Ports\JmsSerializer\JSONSerializer;
use Ads\UI\Web\Slim\Controllers\SignUpPosterController;
use Ads\Posters\Posters;
use Ads\Registration\SignUp\SignUpPoster;
use Ads\Registration\SignUp\SignUpPosterAction;
use Ads\UI\Web\Slim\Handlers\ErrorHandler;
use Ads\UI\Web\Slim\Middleware\EventSubscribersMiddleware;
use Ads\UI\Web\Slim\Middleware\RequestLoggerMiddleware;
use Doctrine\ORM\EntityManager;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;
use Monolog\Processor\MemoryUsageProcessor;
use Monolog\Processor\UidProcessor;
use Monolog\Processor\WebProcessor;
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
        $container[RequestLoggerMiddleware::class] = function (Container $container) {
            return new RequestLoggerMiddleware($container[Logger::class]);
        };
        $container[Logger::class] = function () {
            $logger = new Logger('app');
            $stream = new StreamHandler($this->options['log']['path'], Logger::INFO);
            $stream->pushProcessor(new WebProcessor());
            $stream->pushProcessor(new UidProcessor());
            $stream->pushProcessor(new MemoryUsageProcessor());
            $logger->pushHandler($stream);

            return $logger;
        };
        $container['errorHandler'] = function (Container $container) {
            return new ErrorHandler($container[Logger::class], $this->options['debug']);
        };
    }
}
