<?php
/**
 * PHP version 7.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace Ads\UI\Web\Slim\Controllers;

use Ads\Application\CommandBus\Bus;
use Ads\CodeList\Authentication\Login\LoginAction;
use Ads\CodeList\Authentication\Login\LoginInput;
use Ads\CodeList\Authentication\Login\LoginResponder;
use Ads\CodeList\Posters\Poster;
use Ads\UI\Web\HTTP\ApiResponse;
use Ads\UI\Web\HTTP\HAL\ApiProblems\Problem;
use Ads\UI\Web\HTTP\HAL\ApiProblems\ProblemDetails;
use Ads\UI\Web\HTTP\HAL\Mappings\SlimUriBuilder;
use Ads\UI\Web\HTTP\HAL\Serializer;
use Ads\UI\Web\HTTP\JWT\TokenFactory;
use Psr\Http\Message\ResponseInterface;
use Slim\Http\Request;
use Slim\Router;

class LoginController extends ApiController implements LoginResponder
{
    /** @var TokenFactory */
    private $factory;

    public function __construct(Bus $bus, Router $router, LoginAction $action, TokenFactory $factory)
    {
        parent::__construct($bus, $router);
        $action->attach($this);
        $this->bus->addHandler($action, 'authenticatePoster', LoginInput::class);
        $this->factory = $factory;
    }

    public function authenticate(Request $request): ResponseInterface
    {
        return $this->run($request, LoginInput::withValues($request->getParsedBody()));
    }

    public function respondToInvalidInput(array $errors): void
    {
        $this->response = ApiResponse::unprocessableEntity(
            Problem::failedValidation($errors, ProblemDetails::INVALID_LOGIN_INFORMATION)
        );
    }

    public function respondToUserNotFound(LoginInput $input): void
    {
        $this->response = ApiResponse::unauthorized(
            Problem::failedValidation(
                ['username' => 'Either password or username are incorrect'],
                ProblemDetails::INVALID_CREDENTIALS
            )
        );
    }

    public function respondToIncorrectPassword(LoginInput $input): void
    {
        $this->response = ApiResponse::unauthorized(
            Problem::failedValidation(
                ['username' => 'Either password or username are incorrect'],
                ProblemDetails::INVALID_CREDENTIALS
            )
        );
    }

    /** @throws \ReallySimpleJWT\Exception\TokenBuilderException */
    public function respondToSuccessfulAuthentication(Poster $poster): void
    {
        $uriBuilder = new SlimUriBuilder($this->router, $this->request);
        $serializer = Serializer::hal($uriBuilder);

        $this->response = ApiResponse::ok($serializer->serializeToken($this->factory->builderFor($poster)));
    }
}
