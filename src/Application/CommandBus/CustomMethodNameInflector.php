<?php
/**
 * PHP version 7.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace Ads\Application\CommandBus;

use League\Tactician\Handler\MethodNameInflector\MethodNameInflector;

class CustomMethodNameInflector implements MethodNameInflector
{
    /** @var string */
    private $method;

    public function __construct(string $method)
    {
        $this->method = $method;
    }

    public function inflect($command, $commandHandler): string
    {
        return $this->method;
    }
}
