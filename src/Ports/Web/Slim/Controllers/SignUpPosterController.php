<?php
/**
 * PHP version 7.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace Ads\Ports\Web\Slim\Controllers;

use Ads\Application\Registration\SignUpPosterAction;
use Ads\Ports\Web\Slim\HAL\HalSerializerFactory;
use Ads\Posters\Poster;
use Ads\Posters\PosterInformation;
use Psr\Http\Message\ResponseInterface as Response;
use Slim\Http\Request;
use Slim\Router;

class SignUpPosterController
{
    /** @var SignUpPosterAction */
    private $action;

    /** @var Router */
    private $router;

    public function __construct(SignUpPosterAction $action, Router $router)
    {
        $this->action = $action;
        $this->router = $router;
    }

    public function signUp(Request $request, Response $response)
    {
        $information = PosterInformation::fromInput($request->getParsedBody());
        $poster = Poster::signUp($information);

        $serializer = HalSerializerFactory::createFor($request, $this->router);

        $response->getBody()->write($serializer->serialize($poster));

        return $response
            ->withStatus(201)
            ->withHeader('Content-Type', 'application/hal+json')
            ->withHeader(
                'Location',
                (string)$request->getUri()->withPath($this->router->pathFor('poster', [
                    'username' =>  $information->username()
                ]))
            );
    }
}
