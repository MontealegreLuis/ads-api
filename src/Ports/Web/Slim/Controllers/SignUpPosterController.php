<?php
/**
 * PHP version 7.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace Ads\Ports\Web\Slim\Controllers;

use Ads\Ports\Web\Slim\HAL\ApiProblems\Problem;
use Ads\Ports\Web\Slim\HAL\HalSerializerFactory;
use Ads\Ports\Web\Slim\HAL\Responses\HALResponse;
use Ads\Posters\Poster;
use Ads\Posters\PosterInformation;
use Ads\Registration\SignUp\SignUpPosterAction;
use Ads\Registration\SignUp\SignUpPosterInput;
use Ads\Registration\SignUp\SignUpPosterResponder;
use Ads\Registration\SignUp\UnavailableUsername;
use Psr\Http\Message\ResponseInterface as Response;
use Slim\Http\Request;
use Slim\Router;

class SignUpPosterController implements SignUpPosterResponder
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

        $this->response = HALResponse::created(
            $this->response,
            $this->pathFor('poster', ['username' => $poster->username()]),
            $serializer->serialize($poster)
        );
    }

    public function respondToInvalidPosterInformation(array $errors): void
    {
        $this->response = HALResponse::unprocessableEntity($this->response, Problem::forValidation($errors));
    }

    public function respondToUnavailableUsername(PosterInformation $information, UnavailableUsername $error): void
    {
        $this->response = HALResponse::unprocessableEntity(
            $this->response,
            Problem::unavailableUsername($error)
        );
    }

    private function pathFor(string $routeName, array $parameters): string
    {
        return (string)$this->request->getUri()->withPath($this->router->pathFor($routeName, $parameters));
    }
}
