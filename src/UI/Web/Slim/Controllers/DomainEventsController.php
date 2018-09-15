<?php
/**
 * PHP version 7.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace Ads\UI\Web\Slim\Controllers;

use Ads\Application\CommandBus\Bus;
use Ads\Application\Pagination\Paginator;
use Ads\Application\StoredEvents\ViewEventsInPageAction;
use Ads\Application\StoredEvents\ViewEventsInPageInput;
use Ads\Application\StoredEvents\ViewEventsInPageResponder;
use Ads\UI\Web\HTTP\ApiResponse;
use Ads\UI\Web\HTTP\HAL\ApiProblems\Problem;
use Ads\UI\Web\HTTP\HAL\ApiProblems\ProblemDetails;
use Ads\UI\Web\HTTP\HAL\Mappings\SlimUriBuilder;
use Ads\UI\Web\HTTP\HAL\Serializer;
use Psr\Http\Message\ResponseInterface;
use Slim\Http\Request;
use Slim\Router;

class DomainEventsController extends ApiController implements ViewEventsInPageResponder
{
    public function __construct(Bus $bus, Router $router, ViewEventsInPageAction $action)
    {
        parent::__construct($bus, $router);
        $action->attach($this);
        $this->bus->addHandler($action, 'viewPage', ViewEventsInPageInput::class);
    }

    public function showPage(Request $request): ResponseInterface
    {
        return $this->run($request, ViewEventsInPageInput::withValues($request->getQueryParams()));
    }

    public function respondToEventsInPage(Paginator $events): void
    {
        $serializer = Serializer::hal(new SlimUriBuilder($this->router, $this->request));

        $response = ApiResponse::ok($serializer->serializePaginatedCollection($events, 'events'));

        if ($events->count() === $events->pageSize()) {
            $oneYear = 31536000;
            $response = $response
                ->withAddedHeader('Cache-Control', 'max-age=' . $oneYear)
                ->withAddedHeader('Cache-Control', 's-max-age=' . $oneYear);
        }

        $this->response = $response;
    }

    public function respondToInvalidInput(array $errors): void
    {
        $this->response = ApiResponse::unprocessableEntity(
            Problem::failedValidation($errors, ProblemDetails::INVALID_EVENTS_PAGE)
        );
    }
}
