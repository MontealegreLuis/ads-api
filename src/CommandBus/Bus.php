<?php
/**
 * PHP version 7.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace Ads\CommandBus;

use Doctrine\ORM\EntityManager;
use League\Tactician\CommandBus;
use League\Tactician\Doctrine\ORM\TransactionMiddleware;
use League\Tactician\Handler\CommandHandlerMiddleware;
use League\Tactician\Handler\CommandNameExtractor\ClassNameExtractor;
use League\Tactician\Handler\Locator\InMemoryLocator;

class Bus
{
    /** @var EntityManager */
    private $entityManager;

    /** @var CommandBus */
    private $bus;

    public function __construct(EntityManager $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function addHandler($handler, string $method, string $commandName): void
    {
        $locator = new InMemoryLocator();
        $locator->addHandler($handler, $commandName);
        $handler = new CommandHandlerMiddleware(
            new ClassNameExtractor(),
            $locator,
            new CustomMethodNameInflector($method)
        );
        $this->bus = new CommandBus([
            new TransactionMiddleware($this->entityManager),
            $handler
        ]);
    }

    public function handle($command): void
    {
        $this->bus->handle($command);
    }
}
