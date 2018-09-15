<?php
/**
 * PHP version 7.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace Ads\UI\Web\Slim\Controllers;

use Ads\Application\CommandBus\Bus;
use Ads\CodeList\Posters\Poster;
use Ads\CodeList\Registration\SignUp\SignUpPosterAction;
use Ads\CodeList\Registration\SignUp\SignUpPosterInput;
use Ads\CodeList\Registration\SignUp\SignUpPosterResponder;
use Ads\CodeList\Registration\SignUp\UnavailableUsername;
use Ads\UI\Web\HTTP\ApiResponse;
use Ads\UI\Web\HTTP\HAL\ApiProblems\Problem;
use Ads\UI\Web\HTTP\HAL\ApiProblems\ProblemDetails;
use Ads\UI\Web\HTTP\HAL\Mappings\SlimUriBuilder;
use Ads\UI\Web\HTTP\HAL\Serializer;
use Psr\Http\Message\ResponseInterface;
use Slim\Http\Request;
use Slim\Router;

/**
 * Creates a new Poster
 *
 * @see SignUpPosterControllerTest
 */
class SignUpPosterController extends ApiController implements SignUpPosterResponder
{
    public function __construct(Bus $bus, Router $router, SignUpPosterAction $action)
    {
        parent::__construct($bus, $router);
        $action->attach($this);
        $this->bus->addHandler($action, 'signUpPoster', SignUpPosterInput::class);
    }

    public function signUp(Request $request): ResponseInterface
    {
        return $this->run($request, SignUpPosterInput::withValues($request->getParsedBody()));
    }

    public function respondToPosterSignedUp(Poster $poster): void
    {
        $uriBuilder = new SlimUriBuilder($this->router, $this->request);
        $serializer = Serializer::hal($uriBuilder);

        $this->response = ApiResponse::created(
            $uriBuilder->pathFor('poster', ['username' => $poster->username()]),
            $serializer->serializeItem($poster)
        );
    }

    public function respondToInvalidPosterInformation(array $errors): void
    {
        $this->response = ApiResponse::unprocessableEntity(
            Problem::failedValidation($errors, ProblemDetails::INVALID_POSTER_INFORMATION)
        );
    }

    public function respondToUnavailableUsername(SignUpPosterInput $input, UnavailableUsername $error): void
    {
        $this->response = ApiResponse::unprocessableEntity(
            Problem::failedValidation(['username' => $error->getMessage()], ProblemDetails::UNAVAILABLE_USERNAME)
        );
    }
}
