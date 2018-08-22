<?php
/**
 * PHP version 7.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace Ads\UI\Web\Slim\Middleware;

use Doctrine\DBAL\Logging\DebugStack;
use Doctrine\ORM\EntityManager;
use Psr\Http\Message\ResponseInterface;
use Psr\Log\LoggerInterface;
use Slim\Http\Request;

class QueryLoggerMiddleware
{
    /** @var EntityManager */
    private $manager;

    /** @var LoggerInterface */
    private $logger;

    public function __construct(LoggerInterface $logger, EntityManager $manager)
    {
        $this->logger = $logger;
        $this->manager = $manager;
    }

    public function __invoke(Request $request, ResponseInterface $response, callable $next): ResponseInterface
    {
        /** @var ResponseInterface $response */
        $response = $next($request, $response);

        /** @var DebugStack $sqlLogger */
        $sqlLogger = $this->manager->getConfiguration()->getSQLLogger();

        foreach ($sqlLogger->queries as $query) {
            $this->logger->debug($query['sql'], [
                'params' => $query['params'],
                'types' => $query['types']
            ]);
        }

        return $response;
    }
}
