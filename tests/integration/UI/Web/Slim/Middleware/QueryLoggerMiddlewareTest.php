<?php
/**
 * PHP version 7.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace Ads\UI\Web\Slim\Middleware;

use Ads\CodeList\Posters\Poster;
use Ads\DependencyInjection\WithContainer;
use Doctrine\ORM\EntityManager;
use Monolog\Handler\TestHandler;
use Monolog\Logger;
use PHPUnit\Framework\TestCase;
use Slim\Http\Environment;
use Slim\Http\Request;
use Slim\Http\Response;

class QueryLoggerMiddlewareTest extends TestCase
{
    use WithContainer;

    /** @test */
    function it_logs_a_sql_query()
    {
        /** @var EntityManager $manager */
        $manager = $this->container()->get(EntityManager::class);
        $logHandler = new TestHandler();
        $middleware = new QueryLoggerMiddleware(new Logger('test', [$logHandler]), $manager);
        $request = Request::createFromEnvironment(Environment::mock([
            'REQUEST_METHOD' => 'GET',
            'REQUEST_URI' => '/posters/thomas_anderson',
        ]));
        $response = new Response();
        $query = $manager->createQuery('SELECT p FROM ' . Poster::class . ' p WHERE p.username = :username');
        $query->setParameter('username', 'thomas_anderson')->execute();

        $middleware->__invoke($request, $response, function() use ($response) { return $response; });

        $this->assertTrue($logHandler->hasDebugRecords());
        $this->assertCount(1, $logHandler->getRecords());
    }
}
