<?php
/**
 * PHP version 7.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace Ads\UI\Web\Slim\Controllers;

use Ads\Application\DependencyInjection\ContainerFactory;
use Ads\Builders\A;
use Ads\CodeList\Ads\Ad;
use Ads\CodeList\Clock;
use Ads\CodeList\Posters\Ports\PostersRepository;
use Ads\DependencyInjection\WithContainer;
use Ads\UI\Web\HTTP\ContentType;
use Ads\UI\Web\Slim\Application;
use Doctrine\ORM\EntityManager;
use PHPUnit\Framework\TestCase;
use Slim\Http\Environment;
use Slim\Http\Request;
use Teapot\StatusCode\All as Status;

class DraftAdControllerTest extends TestCase
{
    use WithContainer;

    /** @test */
    function it_returns_api_problem_if_validation_fails()
    {
        $env = Environment::mock([
            'REQUEST_METHOD' => 'POST',
            'REQUEST_URI' => '/draft-ads',
        ]);
        $request = Request::createFromEnvironment($env)->withParsedBody([
            'title' => '',
            'description' => '',
        ]);
        $this->app->getContainer()->set('request', $request);
        $response = $this->app->run(true);

        $this->assertSame(Status::UNPROCESSABLE_ENTITY, $response->getStatusCode());
        $this->assertSame(ContentType::PROBLEM_JSON, $response->getHeaderLine('Content-Type'));
        $this->assertSame(
            '{"errors":{"title":"This value should not be blank.","description":"This value should not be blank."},"code":"DRAFT-INV-INPUT","details":"Draft Ad information is invalid","title":"Unprocessable Entity","type":"http:\/\/www.w3.org\/Protocols\/rfc2616\/rfc2616-sec10.html","status":422}',
            (string)$response->getBody()
        );
    }

    /** @test */
    function it_returns_api_problem_if_poster_cannot_be_found()
    {
        $env = Environment::mock([
            'REQUEST_METHOD' => 'POST',
            'REQUEST_URI' => '/draft-ads',
        ]);
        $request = Request::createFromEnvironment($env)->withParsedBody([
            'title' => 'Test title',
            'description' => 'Test description',
            'author' => 'elliot_anderson',
        ]);
        $this->app->getContainer()->set('request', $request);
        $response = $this->app->run(true);

        $this->assertSame(Status::NOT_FOUND, $response->getStatusCode());
        $this->assertSame(ContentType::PROBLEM_JSON, $response->getHeaderLine('Content-Type'));
        $this->assertSame(
            '{"errors":{"username":"Cannot find user with username elliot_anderson"},"code":"POSTER-NOT-FOUND","details":"Unknown poster username","title":"Not Found","type":"http:\/\/www.w3.org\/Protocols\/rfc2616\/rfc2616-sec10.html","status":404}',
            (string)$response->getBody()
        );
    }

    /** @test */
    function it_returns_created_draft_ad()
    {
        Clock::freezeTimeAt(1537052912);
        $poster = A::poster()
            ->withUsername('elliot_alderson')
            ->withName('Elliot Alderson')
            ->withEmail('elliot@example.com')
            ->build();
        $this->posters->add($poster);
        $env = Environment::mock([
            'REQUEST_METHOD' => 'POST',
            'REQUEST_URI' => '/draft-ads',
        ]);
        $request = Request::createFromEnvironment($env)->withParsedBody([
            'title' => 'Test title',
            'description' => 'Test description',
            'author' => 'elliot_alderson',
        ]);
        $this->app->getContainer()->set('request', $request);
        $response = $this->app->run(true);

        $this->assertSame(Status::CREATED, $response->getStatusCode());
        $this->assertSame(ContentType::HAL_JSON, $response->getHeaderLine('Content-Type'));
        $this->assertSame(
            '{"id":2,"title":"Test title","description":"Test description","created_at":1537052912,"last_updated_at":null,"published_on":null,"_embedded":{"author":{"username":"elliot_alderson","name":"Elliot Alderson","email":"elliot@example.com","_links":{"self":{"href":"http://localhost/posters/elliot_alderson"}}}},"_links":{"author":{"href":"http://localhost/posters/elliot_alderson"},"self":{"href":"http://localhost/draft-ads/2"}}}',
            (string)$response->getBody()
        );
    }

    /** @before */
    function configure()
    {
        Clock::unfreezeTime();
        $this->app = new Application(ContainerFactory::new());
        $container = $this->container();
        $manager = $container->get(EntityManager::class);
        $this->posters = new PostersRepository($manager);
        $container->get(EntityManager::class)
            ->createQuery('DELETE FROM ' . Ad::class)
            ->execute();
    }

    /** @after */
    function after()
    {
        Clock::unfreezeTime();
    }

    /** @var Application */
    private $app;

    /** @var \Ads\CodeList\Posters\Posters */
    private $posters;
}
