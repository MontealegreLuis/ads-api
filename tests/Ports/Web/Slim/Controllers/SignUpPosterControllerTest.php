<?php
/**
 * PHP version 7.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace Ads\Ports\Web\Slim\Controllers;

use Ads\Ports\Web\Slim\Application;
use PHPUnit\Framework\TestCase;
use Slim\Http\Environment;
use Slim\Http\Request;

class SignUpPosterControllerTest extends TestCase
{
    /** @test */
    function it_returns_successful_status_code_and_content_after_signing_up_a_poster()
    {
        $app = new Application();
        $env = Environment::mock([
            'REQUEST_METHOD' => 'POST',
            'REQUEST_URI' => '/sign-up',
        ]);
        $req = Request::createFromEnvironment($env)->withParsedBody([
            'username' => 'neo',
            'password' => 'ilovemyjob',
            'name' => 'Thomas Anderson',
            'email' => 'thomas.anderson@thematrix.org'
        ]);
        $app->getContainer()['request'] = $req;
        $response = $app->run(true);

        $this->assertSame(201, $response->getStatusCode());
        $this->assertSame('application/hal+json', $response->getHeader('Content-Type')[0]);
        $this->assertSame(
            '{"username":"neo","password":"ilovemyjob","name":"Thomas Anderson","email":"thomas.anderson@thematrix.org"}',
            (string)$response->getBody()
        );
    }
}
