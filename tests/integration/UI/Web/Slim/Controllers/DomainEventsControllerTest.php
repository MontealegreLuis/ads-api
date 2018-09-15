<?php
/**
 * PHP version 7.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace Ads\UI\Web\Slim\Controllers;

use Ads\Application\DependencyInjection\ContainerFactory;
use Ads\Application\DomainEvents\EventStore;
use Ads\Application\DomainEvents\StoredEventFactory;
use Ads\Builders\A;
use Ads\DataStorage\WithTableCleanup;
use Ads\DependencyInjection\WithContainer;
use Ads\UI\Web\HTTP\ContentType;
use Ads\UI\Web\Slim\Application;
use PHPUnit\Framework\TestCase;
use Slim\Http\Environment;
use Slim\Http\Request;
use Teapot\StatusCode\All as Status;

class DomainEventsControllerTest extends TestCase
{
    use WithContainer, WithTableCleanup;

    /** @test */
    function it_returns_empty_paginator_if_no_event_has_been_published()
    {
        $env = Environment::mock([
            'REQUEST_METHOD' => 'GET',
            'REQUEST_URI' => '/events',
        ]);
        $request = Request::createFromEnvironment($env)->withQueryParams([
            'page' => 1,
        ]);
        $this->app->getContainer()->set('request', $request);
        $response = $this->app->run(true);

        $this->assertSame(Status::OK, $response->getStatusCode());
        $this->assertSame(ContentType::HAL_JSON, $response->getHeaderLine('Content-Type'));
        $this->assertSame(
            '{"count":0,"total":0,"_embedded":[],"_links":{"self":{"href":"http://localhost/events?page=1"},"first":{"href":"http://localhost/events?page=1"},"last":{"href":"http://localhost/events?page=1"}}}',
            (string)$response->getBody()
        );
    }

    /** @test */
    function it_returns_unprocessable_entity_if_an_invalid_page_number_is_given()
    {
        $env = Environment::mock([
            'REQUEST_METHOD' => 'GET',
            'REQUEST_URI' => '/events',
        ]);
        $request = Request::createFromEnvironment($env)->withQueryParams([
            'page' => 'NaN',
        ]);
        $this->app->getContainer()->set('request', $request);
        $response = $this->app->run(true);

        $this->assertSame(Status::UNPROCESSABLE_ENTITY, $response->getStatusCode());
        $this->assertSame(ContentType::PROBLEM_JSON, $response->getHeaderLine('Content-Type'));
        $this->assertSame(
            '{"errors":{"page":"This value should be greater than or equal to 1."},"code":"EVT-INV-PAGE","details":"Invalid events page number","title":"Unprocessable Entity","type":"http:\/\/www.w3.org\/Protocols\/rfc2616\/rfc2616-sec10.html","status":422}',
            (string)$response->getBody()
        );
    }

    /** @test */
    function it_returns_correct_elements_given_a_page_number()
    {
        $letterA = 97;
        $now = 1535249642;
        foreach (range(1, 15) as $_) {
            $event = A::posterHasSignedUpEvent()
                ->withName(strtoupper(\chr($letterA)))
                ->withUsername(\chr($letterA) . '12345')
                ->withEmail(\chr($letterA) . '@example.com')
                ->occurredOn($now)
                ->build();
            $letterA++;
            $now += 60;
            $this->eventStore->append($this->factory->from($event));
        }

        $env = Environment::mock([
            'REQUEST_METHOD' => 'GET',
            'REQUEST_URI' => '/events',
        ]);
        $request = Request::createFromEnvironment($env)->withQueryParams([
            'page' => 2,
        ]);
        $this->app->getContainer()->set('request', $request);
        $response = $this->app->run(true);

        $this->assertSame(Status::OK, $response->getStatusCode());
        $this->assertSame(ContentType::HAL_JSON, $response->getHeaderLine('Content-Type'));
        $this->assertSame(
            '{"count":5,"total":15,"_embedded":[{"event_id":6,"body":"{\"occurred_on\":1535249942,\"username\":\"f12345\",\"name\":\"F\",\"email\":\"f@example.com\"}","type":"Ads\\\\CodeList\\\\Posters\\\\PosterHasSignedUp","occurred_on":1535249942},{"event_id":7,"body":"{\"occurred_on\":1535250002,\"username\":\"g12345\",\"name\":\"G\",\"email\":\"g@example.com\"}","type":"Ads\\\\CodeList\\\\Posters\\\\PosterHasSignedUp","occurred_on":1535250002},{"event_id":8,"body":"{\"occurred_on\":1535250062,\"username\":\"h12345\",\"name\":\"H\",\"email\":\"h@example.com\"}","type":"Ads\\\\CodeList\\\\Posters\\\\PosterHasSignedUp","occurred_on":1535250062},{"event_id":9,"body":"{\"occurred_on\":1535250122,\"username\":\"i12345\",\"name\":\"I\",\"email\":\"i@example.com\"}","type":"Ads\\\\CodeList\\\\Posters\\\\PosterHasSignedUp","occurred_on":1535250122},{"event_id":10,"body":"{\"occurred_on\":1535250182,\"username\":\"j12345\",\"name\":\"J\",\"email\":\"j@example.com\"}","type":"Ads\\\\CodeList\\\\Posters\\\\PosterHasSignedUp","occurred_on":1535250182}],"_links":{"self":{"href":"http://localhost/events?page=2"},"first":{"href":"http://localhost/events?page=1"},"next":{"href":"http://localhost/events?page=3"},"prev":{"href":"http://localhost/events?page=1"},"last":{"href":"http://localhost/events?page=3"}}}',
            (string)$response->getBody()
        );
    }

    /** @before */
    function configure()
    {
        $this->empty('events');
        $container = ContainerFactory::new();
        $this->app = new Application($container);
        $this->eventStore = $container->get(EventStore::class);
        $this->factory = $container->get(StoredEventFactory::class);
    }

    /** @var Application */
    private $app;

    /** @var EventStore */
    private $eventStore;

    /** @var StoredEventFactory */
    private $factory;
}
