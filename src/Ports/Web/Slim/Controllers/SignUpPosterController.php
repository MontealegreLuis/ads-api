<?php
/**
 * PHP version 7.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace Ads\Ports\Web\Slim\Controllers;

use Ads\Application\Registration\CanSignUpPosters;
use Ads\Application\Registration\SignUpPosterAction;
use Ads\Application\Registration\SignUpPosterInput;
use Ads\Ports\Web\Slim\HAL\HalSerializerFactory;
use Ads\Posters\Poster;
use Ads\Posters\PosterInformation;
use Ads\Registration\UnavailableUsername;
use Psr\Http\Message\ResponseInterface as Response;
use RuntimeException;
use Slim\Http\Request;
use Slim\Router;

class SignUpPosterController implements CanSignUpPosters
{
    /** @var SignUpPosterAction */
    private $action;

    /** @var Router */
    private $router;

    /** @var Response */
    private $response;

    /** @var Request */
    private $request;

    public function __construct(SignUpPosterAction $action, Router $router)
    {
        $this->action = $action;
        $this->action->attach($this);
        $this->router = $router;
    }

    public function signUp(Request $request, Response $response): Response
    {
        $this->request = $request;
        $this->response = $response;

        $this->action->signUp(SignUpPosterInput::withValues($request->getParsedBody()));

        return $this->response;
    }

    public function respondToPosterSignedUp(Poster $poster): void
    {
        $serializer = HalSerializerFactory::createFor($this->request, $this->router);

        $this->response->getBody()->write($serializer->serialize($poster));

        $this->response = $this->response
            ->withStatus(201)
            ->withHeader('Content-Type', 'application/hal+json')
            ->withHeader(
                'Location',
                (string)$this->request->getUri()->withPath($this->router->pathFor('poster', [
                    'username' => (string)$poster->username()
                ]))
            );
    }

    public function respondToInvalidPosterInformation(array $errors): void
    {
        throw new RuntimeException('TODO');
    }

    public function respondToUnavailableUsername(PosterInformation $information, UnavailableUsername $exception): void
    {
        throw new RuntimeException('TODO');
    }
}
