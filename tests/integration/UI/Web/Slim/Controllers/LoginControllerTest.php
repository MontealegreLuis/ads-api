<?php
/**
 * PHP version 7.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace Ads\UI\Web\Slim\Controllers;

use Ads\Application\DependencyInjection\ContainerFactory;
use Ads\Builders\A;
use Ads\CodeList\Posters\Ports\PostersRepository;
use Ads\CodeList\Posters\Poster;
use Ads\UI\Web\HTTP\ContentType;
use Ads\UI\Web\HTTP\JWT\TokenFactory;
use Ads\UI\Web\Slim\Application;
use Doctrine\ORM\EntityManager;
use PHPUnit\Framework\TestCase;
use ReallySimpleJWT\Token;
use Slim\Http\Environment;
use Slim\Http\Request;
use Teapot\StatusCode\All as Status;

class LoginControllerTest extends TestCase
{
    /** @test */
    function it_returns_an_api_problem_description_if_validation_fails()
    {
        $env = Environment::mock([
            'REQUEST_METHOD' => 'POST',
            'REQUEST_URI' => '/authenticate',
        ]);
        $request = Request::createFromEnvironment($env)->withParsedBody([
            'username' => 'neo', // name is too short
            'password' => '', // password is empty
        ]);
        $this->app->getContainer()->set('request', $request);
        $response = $this->app->run(true);

        $this->assertSame(Status::UNPROCESSABLE_ENTITY, $response->getStatusCode());
        $this->assertSame(ContentType::PROBLEM_JSON, $response->getHeaderLine('Content-Type'));
        $this->assertSame(
            '{"errors":{"username":"This value is too short. It should have 5 characters or more.","password":"This value should not be blank."},"code":"LOGIN-INV-INPUT","details":"Login credentials are invalid","title":"Unprocessable Entity","type":"http:\/\/www.w3.org\/Protocols\/rfc2616\/rfc2616-sec10.html","status":422}',
            (string)$response->getBody()
        );
    }

    /** @test */
    function it_returns_an_api_problem_description_if_username_cannot_be_found()
    {
        $env = Environment::mock([
            'REQUEST_METHOD' => 'POST',
            'REQUEST_URI' => '/authenticate',
        ]);
        $request = Request::createFromEnvironment($env)->withParsedBody([
            'username' => 'elliot_alderson', // there is no poster with this username
            'password' => 'ilovemyjob',
        ]);
        $this->app->getContainer()->set('request', $request);
        $response = $this->app->run(true);

        $this->assertSame(Status::UNAUTHORIZED, $response->getStatusCode());
        $this->assertSame(ContentType::PROBLEM_JSON, $response->getHeaderLine('Content-Type'));
        $this->assertSame(
            '{"errors":{"username":"Either password or username are incorrect"},"code":"INV_CRED_LOGIN","details":"Either password or username are incorrect","title":"Unprocessable Entity","type":"http:\/\/www.w3.org\/Protocols\/rfc2616\/rfc2616-sec10.html","status":422}',
            (string)$response->getBody()
        );
    }

    /** @test */
    function it_returns_an_api_problem_description_if_password_is_incorrect()
    {
        $poster = A::poster()->withUsername('elliot_alderson')->withPassword('ilovemyjob')->build();
        $this->posters->add($poster);
        $env = Environment::mock([
            'REQUEST_METHOD' => 'POST',
            'REQUEST_URI' => '/authenticate',
        ]);
        $request = Request::createFromEnvironment($env)->withParsedBody([
            'username' => 'elliot_alderson',
            'password' => 'wrong password', // wrong password
        ]);
        $this->app->getContainer()->set('request', $request);
        $response = $this->app->run(true);

        $this->assertSame(Status::UNAUTHORIZED, $response->getStatusCode());
        $this->assertSame(ContentType::PROBLEM_JSON, $response->getHeaderLine('Content-Type'));
        $this->assertSame(
            '{"errors":{"username":"Either password or username are incorrect"},"code":"INV_CRED_LOGIN","details":"Either password or username are incorrect","title":"Unprocessable Entity","type":"http:\/\/www.w3.org\/Protocols\/rfc2616\/rfc2616-sec10.html","status":422}',
            (string)$response->getBody()
        );
    }

    /** @test */
    function it_returns_ok_response_if_user_is_successfully_authenticated()
    {
        $poster = A::poster()->withUsername('elliot_alderson')->withPassword('ilovemyjob')->build();
        $this->posters->add($poster);
        $env = Environment::mock([
            'REQUEST_METHOD' => 'POST',
            'REQUEST_URI' => '/authenticate',
        ]);
        $request = Request::createFromEnvironment($env)->withParsedBody([
            'username' => 'elliot_alderson',
            'password' => 'ilovemyjob',
        ]);
        $this->app->getContainer()->set('request', $request);
        $response = $this->app->run(true);

        $this->assertSame(Status::OK, $response->getStatusCode());
        $this->assertSame(ContentType::HAL_JSON, $response->getHeaderLine('Content-Type'));

        $body = json_decode((string)$response->getBody(), true);
        $this->assertTrue(Token::validate($body['token'], $this->factory->secret()));
    }

    /** @before */
    function configure()
    {
        $container = ContainerFactory::new();
        $this->app = new Application($container);
        $manager = $container->get(EntityManager::class);
        $this->posters = new PostersRepository($manager);
        $this->factory = $container->get(TokenFactory::class);
        $manager
            ->createQuery('DELETE FROM ' . Poster::class)
            ->execute();
    }

    /** @var Application */
    private $app;

    /** @var \Ads\CodeList\Posters\Posters */
    private $posters;

    /** @var TokenFactory */
    private $factory;
}
