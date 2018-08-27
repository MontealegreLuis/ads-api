<?php
/**
 * PHP version 7.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace Ads\Application\DependencyInjection;

use Ads\Application\CommandBus\Bus;
use Ads\Application\DataStorage\EntityManagerFactory;
use Ads\Application\DomainEvents\EventStore;
use Ads\Application\DomainEvents\Ports\EventStoreRepository;
use Ads\Application\DomainEvents\Ports\JSONSerializer;
use Ads\Application\DomainEvents\StoredEventFactory;
use Ads\Application\DomainEvents\StoredEventsSubscriber;
use Ads\Application\StoredEvents\ViewEventsInPageAction;
use Ads\CodeList\Authentication\Login\LoginAction;
use Ads\CodeList\Posters\Ports\PosterRepository;
use Ads\CodeList\Posters\Posters;
use Ads\CodeList\Registration\SignUp\SignUpPosterAction;
use Ads\UI\Web\HTTP\JWT\TokenFactory;
use Ads\UI\Web\Slim\Controllers\DomainEventsController;
use Ads\UI\Web\Slim\Controllers\LoginController;
use Ads\UI\Web\Slim\Controllers\SignUpPosterController;
use Ads\UI\Web\Slim\Handlers\ErrorHandler;
use Ads\UI\Web\Slim\Middleware\EventSubscribersMiddleware;
use Ads\UI\Web\Slim\Middleware\QueryLoggerMiddleware;
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
    /** @var array */
    private $options;

    public function __construct(array $options)
    {
        $this->options = $options;
    }

    public function register(Container $container): void
    {
        $container[EntityManager::class] = function () {
            return EntityManagerFactory::new($this->options);
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
                new SignUpPosterAction($container[Posters::class]),
                $container['router']
            );
        };
        $container[Bus::class] = function (Container $container) {
            return new Bus($container[EntityManager::class]);
        };
        $container[StoredEventsSubscriber::class] = function (Container $container) {
            return new StoredEventsSubscriber(
                $container[EventStoreRepository::class],
                $container[StoredEventFactory::class]
            );
        };
        $container[StoredEventFactory::class] = function () {
            return new StoredEventFactory(new JSONSerializer());
        };
        $container[EventSubscribersMiddleware::class] = function (Container $container) {
            return new EventSubscribersMiddleware($container[StoredEventsSubscriber::class]);
        };
        $container[RequestLoggerMiddleware::class] = function (Container $container) {
            return new RequestLoggerMiddleware($container[Logger::class]);
        };
        $container[Logger::class] = function () {
            $logger = new Logger('app');
            $stream = new StreamHandler($this->options['log']['path'], Logger::DEBUG);
            $stream->pushProcessor(new WebProcessor());
            $stream->pushProcessor(new UidProcessor());
            $stream->pushProcessor(new MemoryUsageProcessor());
            $logger->pushHandler($stream);

            return $logger;
        };
        $container[QueryLoggerMiddleware::class] = function (Container $container) {
            return new QueryLoggerMiddleware($container[Logger::class], $container[EntityManager::class]);
        };
        $container['errorHandler'] = function (Container $container) {
            return new ErrorHandler($container[Logger::class], $this->options['debug']);
        };
        $container[DomainEventsController::class] = function (Container $container) {
            return new DomainEventsController(
                $container[Bus::class],
                $container[ViewEventsInPageAction::class],
                $container['router']
            );
        };
        $container[ViewEventsInPageAction::class] = function (Container $container) {
            return new ViewEventsInPageAction($container[EventStore::class]);
        };
        $container[EventStore::class] = function (Container $container) {
            return new EventStoreRepository($container[EntityManager::class]);
        };
        $container[LoginController::class] = function (Container $container) {
            return new LoginController(
                $container[Bus::class],
                $container[LoginAction::class],
                $container['router'],
                $container[TokenFactory::class]
            );
        };
        $container[TokenFactory::class] = function () {
            return new TokenFactory($this->options['jwt']['secret']);
        };
        $container[LoginAction::class] = function (Container $container) {
            return new LoginAction($container[Posters::class]);
        };
    }
}
