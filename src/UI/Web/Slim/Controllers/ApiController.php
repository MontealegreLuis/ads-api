<?php
/**
 * PHP version 7.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace Ads\UI\Web\Slim\Controllers;

use Ads\Application\CommandBus\Bus;
use Ads\Application\Validation\InputValidator;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Slim\Http\Request;
use Slim\Router;

abstract class ApiController
{
    /** @var Bus */
    protected $bus;

    /** @var Request */
    protected $request;

    /** @var Router */
    protected $router;

    /** @var ResponseInterface */
    protected $response;

    protected function __construct(Bus $bus, Router $router)
    {
        $this->bus = $bus;
        $this->router = $router;
    }

    protected function run(ServerRequestInterface $request, InputValidator $input): ResponseInterface
    {
        $this->request = $request;

        $this->bus->handle($input);

        return $this->response;
    }
}
