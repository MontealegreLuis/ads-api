<?php
/**
 * PHP version 7.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace Ads\Ports\Web\Slim\Middleware;

use Psr\Http\Message\ResponseInterface;
use Psr\Log\LoggerInterface;
use Slim\Http\Request;
use Slim\Route;

class RequestLoggerMiddleware
{
    /** @var LoggerInterface */
    private $logger;

    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    /**
     * Log the current request information and its matched route, if any
     */
    public function __invoke(Request $request, ResponseInterface $response, callable $next)
    {
        $route = $request->getAttribute('route');

        /** @var ResponseInterface $response */
        $response = $next($request, $response);

        if ($route === null) {
            $this->logNotFound($response);
            return $response;
        }

        $statusCode = $response->getStatusCode();
        if (300 <= $statusCode && $statusCode <= 400) {
            $this->logRedirect($response);
            return $response;
        }

        $this->logRouteMatched($route, $request, $response);

        return $response;
    }

    private function logNotFound(ResponseInterface $response): void
    {
        $this->logger->info('No route matched', [
            'status' => $response->getStatusCode(),
            'phrase' => $response->getReasonPhrase(),
        ]);
    }

    private function logRedirect(ResponseInterface $response): void
    {
        $this->logger->info('Redirect', [
            'status' => $response->getStatusCode(),
            'phrase' => $response->getReasonPhrase(),
            'location' => $response->getHeader('Location')[0],
        ]);
    }

    private function logRouteMatched(Route $route, Request $request, ResponseInterface $response): void
    {
        $this->logger->info('Route matched', [
            'status' => $response->getStatusCode(),
            'phrase' => $response->getReasonPhrase(),
            'route' => $route->getName(),
            'params' => $route->getArguments(),
            'request' => $request->getParams(),
        ]);
    }
}
