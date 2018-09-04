<?php
/**
 * PHP version 7.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace Ads\UI\Web\Slim\Middleware;

use Monolog\Handler\TestHandler;
use Monolog\Logger;
use PHPUnit\Framework\TestCase;
use Slim\Http\Environment;
use Slim\Http\Request;
use Slim\Http\Response;
use Slim\Route;
use Teapot\StatusCode\All as Status;

class RequestLoggerMiddlewareTest extends TestCase
{
    /** @test */
    function it_logs_a_not_found_error()
    {
        $logHandler = new TestHandler();
        $middleware = new RequestLoggerMiddleware(new Logger('test', [$logHandler]));
        $request = Request::createFromEnvironment(Environment::mock([
            'REQUEST_METHOD' => 'GET',
            'REQUEST_URI' => '/foo',
        ]));
        $response = new Response(Status::NOT_FOUND);
        $next = function () use ($response) {
            return $response;
        };

        $middleware->__invoke($request, $response, $next);

        $this->assertTrue($logHandler->hasInfoRecords());
        $this->assertCount(1, $logHandler->getRecords());
        $this->assertEquals('No route matched', $logHandler->getRecords()[0]['message']);
        $context = $logHandler->getRecords()[0]['context'];
        $this->assertEquals([
            'status' => 404,
            'phrase' => 'Not Found',
        ], $context);
    }

    /** @test */
    function it_logs_a_redirect()
    {
        $logHandler = new TestHandler();
        $middleware = new RequestLoggerMiddleware(new Logger('test', [$logHandler]));
        $request = Request::createFromEnvironment(Environment::mock([
            'REQUEST_METHOD' => 'GET',
            'REQUEST_URI' => '/users',
        ]))->withAttribute('route', 'test');
        $response = (new Response(Status::MOVED_PERMANENTLY))
            ->withHeader('Location', 'http://localhost/posters');
        $next = function () use ($response) {
            return $response;
        };

        $middleware->__invoke($request, $response, $next);

        $this->assertTrue($logHandler->hasInfoRecords());
        $this->assertCount(1, $logHandler->getRecords());
        $this->assertEquals('Redirect', $logHandler->getRecords()[0]['message']);
        $context = $logHandler->getRecords()[0]['context'];
        $this->assertEquals([
            'status' => 301,
            'phrase' => 'Moved Permanently',
            'location' => 'http://localhost/posters',
        ], $context);
    }

    /** @test */
    function it_logs_a_matched_route()
    {
        $logHandler = new TestHandler();
        $middleware = new RequestLoggerMiddleware(new Logger('test', [$logHandler]));
        $response = new Response(Status::OK);
        $response->write('[]');
        $next = function () use ($response) {
            return $response;
        };
        $route = new Route(['GET'], '/posters', $next);
        $route->setName('posters');
        $request = Request::createFromEnvironment(Environment::mock([
            'REQUEST_METHOD' => 'GET',
            'REQUEST_URI' => '/posters',
        ]))->withAttribute('route', $route);

        $middleware->__invoke($request, $response, $next);

        $this->assertTrue($logHandler->hasInfoRecords());
        $this->assertCount(1, $logHandler->getRecords());
        $this->assertEquals('Route matched', $logHandler->getRecords()[0]['message']);
        $context = $logHandler->getRecords()[0]['context'];
        $this->assertEquals([
            'status' => 200,
            'phrase' => 'OK',
            'route' => 'posters',
            'params' => [],
            'request' => [],
        ], $context);
    }
}
