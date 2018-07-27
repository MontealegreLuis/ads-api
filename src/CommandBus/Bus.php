<?php
/**
 * PHP version 7.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace Ads\CommandBus;

use League\Tactician\CommandBus;
use League\Tactician\Handler\CommandHandlerMiddleware;
use League\Tactician\Handler\CommandNameExtractor\ClassNameExtractor;
use League\Tactician\Handler\Locator\InMemoryLocator;

class Bus
{
    public static function for($handler, string $method, string $commandName): CommandBus
    {
        $locator = new InMemoryLocator();
        $locator->addHandler($handler, $commandName);
        $handler = new CommandHandlerMiddleware(
            new ClassNameExtractor(),
            $locator,
            new CustomMethodNameInflector($method)
        );
        return new CommandBus([$handler]);
    }
}
