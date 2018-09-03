<?php
/**
 * PHP version 7.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace Ads\UI\Web\Slim\Handlers;

use Monolog\Handler\TestHandler;
use Monolog\Logger;
use PHPUnit\Framework\TestCase;
use RuntimeException;
use Slim\Http\Environment;
use Slim\Http\Request;
use Slim\Http\Response;
use Teapot\StatusCode\All as Status;

class ErrorHandlerTest extends TestCase
{
    /** @test */
    function it_logs_an_exception()
    {
        $logHandler = new TestHandler();
        $request = Request::createFromEnvironment(Environment::mock([
            'REQUEST_METHOD' => 'GET',
            'REQUEST_URI' => '/posters',
        ]));
        $response = new Response(Status::INTERNAL_SERVER_ERROR);
        $errorHandler = new ErrorHandler(new Logger('test', [$logHandler]));
        $exception = new RuntimeException('Ooops!');

        $errorHandler->__invoke($request, $response, $exception);

        $this->assertTrue($logHandler->hasErrorRecords());
        $this->assertCount(1, $logHandler->getRecords());
        $this->assertStringStartsWith('RuntimeException: Ooops!', $logHandler->getRecords()[0]['message']);
    }
}
