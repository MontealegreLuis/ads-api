<?php
/**
 * PHP version 7.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace Ads\UI\Web\Slim\Controllers;

use Ads\Application\CommandBus\Bus;
use Ads\CodeList\Posters\Poster;
use Ads\CodeList\Posters\PosterInformation;
use Ads\CodeList\Registration\SignUp\SignUpPosterAction;
use Ads\CodeList\Registration\SignUp\SignUpPosterInput;
use Ads\CodeList\Registration\SignUp\SignUpPosterResponder;
use Ads\CodeList\Registration\SignUp\UnavailableUsername;
use Ads\UI\Web\HTTP\ApiResponse;
use Ads\UI\Web\HTTP\HAL\ApiProblems\Problem;
use Ads\UI\Web\HTTP\HAL\ApiProblems\ProblemDetails;
use Ads\UI\Web\HTTP\HAL\Mappings\SlimUriBuilder;
use Ads\UI\Web\HTTP\HAL\Serializer;
use Psr\Http\Message\ResponseInterface as Response;
use Slim\Http\Request;
use Slim\Router;

/**
 * Creates a new Poster
 *
 * @see SignUpPosterControllerTest
 */
class SignUpPosterController implements SignUpPosterResponder
{
    /** @var Bus */
    private $bus;

    /** @var Router */
    private $router;

    /** @var Response */
    private $response;

    /** @var Request */
    private $request;

    public function __construct(Bus $bus, SignUpPosterAction $action, Router $router)
    {
        $action->attach($this);
        $this->bus = $bus;
        $this->bus->addHandler($action, 'signUpPoster', SignUpPosterInput::class);
        $this->router = $router;
    }

    public function signUp(Request $request): Response
    {
        $this->request = $request;

        $this->bus->handle(SignUpPosterInput::withValues($request->getParsedBody()));

        return $this->response;
    }

    public function respondToPosterSignedUp(Poster $poster): void
    {
        $uriBuilder = new SlimUriBuilder($this->router, $this->request);
        $serializer = Serializer::hal($uriBuilder);

        $this->response = ApiResponse::created(
            $uriBuilder->pathFor('poster', ['username' => $poster->username()]),
            $serializer->serialize($poster)
        );
    }

    public function respondToInvalidPosterInformation(array $errors): void
    {
        $this->response = ApiResponse::unprocessableEntity(
            Problem::failedValidation($errors, ProblemDetails::INVALID_POSTER_INFORMATION)
        );
    }

    public function respondToUnavailableUsername(PosterInformation $information, UnavailableUsername $error): void
    {
        $this->response = ApiResponse::unprocessableEntity(
            Problem::failedValidation(['username' => $error->getMessage()], ProblemDetails::UNAVAILABLE_USERNAME)
        );
    }
}
