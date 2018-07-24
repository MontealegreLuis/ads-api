<?php
/**
 * PHP version 7.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace Ads\Ports\Web\Slim\Controllers;

use Ads\Ports\Web\Slim\Application;
use Ads\Ports\Web\Slim\DependencyInjection\ApplicationServices;
use PHPUnit\Framework\TestCase;
use Slim\Http\Environment;
use Slim\Http\Request;
use Teapot\StatusCode\All as Status;

class SignUpPosterControllerTest extends TestCase
{
    /** @test */
    function it_returns_successful_status_code_and_content_after_signing_up_a_poster()
    {
        $app = new Application(new ApplicationServices());
        $env = Environment::mock([
            'REQUEST_METHOD' => 'POST',
            'REQUEST_URI' => '/sign-up',
        ]);
        $req = Request::createFromEnvironment($env)->withParsedBody([
            'username' => 'thomas_anderson',
            'password' => 'ilovemyjob',
            'name' => 'Thomas Anderson',
            'email' => 'thomas.anderson@thematrix.org'
        ]);
        $app->getContainer()['request'] = $req;
        $response = $app->run(true);

        $this->assertSame(Status::CREATED, $response->getStatusCode());
        $this->assertSame('application/hal+json', $response->getHeader('Content-Type')[0]);
        $this->assertSame(
            '{"username":"thomas_anderson","name":"Thomas Anderson","email":"thomas.anderson@thematrix.org","_links":{"self":{"href":"http://localhost/poster/thomas_anderson"}}}',
            (string)$response->getBody()
        );
    }

    /** @test */
    function it_returns_an_api_problem_description_if_validation_fails()
    {
        $app = new Application(new ApplicationServices());
        $env = Environment::mock([
            'REQUEST_METHOD' => 'POST',
            'REQUEST_URI' => '/sign-up',
        ]);
        $req = Request::createFromEnvironment($env)->withParsedBody([
            'username' => 'neo', // name is too short
            'password' => 'ilovemyjob',
            'name' => 'Thomas Anderson',
            'email' => 'thomas.anderson_at_thematrix.org' // not an email
        ]);
        $app->getContainer()['request'] = $req;
        $response = $app->run(true);

        $this->assertSame(Status::UNPROCESSABLE_ENTITY, $response->getStatusCode());
        $this->assertSame('application/problem+json', $response->getHeader('Content-Type')[0]);
        $this->assertSame(
            '{"errors":{"username":"This value is too short. It should have 5 characters or more.","email":"This value is not a valid email address."},"code":1,"title":"Unprocessable Entity","type":"http:\/\/www.w3.org\/Protocols\/rfc2616\/rfc2616-sec10.html","status":422}',
            (string)$response->getBody()
        );
    }
}
