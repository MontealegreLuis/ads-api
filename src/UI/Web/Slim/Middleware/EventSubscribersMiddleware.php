<?php
/**
 * PHP version 7.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace Ads\UI\Web\Slim\Middleware;

use Ads\Application\DomainEvents\EventPublisher;
use Ads\Application\DomainEvents\StoredEventsSubscriber;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

class EventSubscribersMiddleware
{
    /** @var StoredEventsSubscriber */
    private $storedEventsSubscriber;

    public function __construct(StoredEventsSubscriber $storedEventsSubscriber)
    {
        $this->storedEventsSubscriber = $storedEventsSubscriber;
    }

    public function __invoke(RequestInterface $request, ResponseInterface $response, callable $next): ResponseInterface
    {
        EventPublisher::subscribe($this->storedEventsSubscriber);

        return $next($request, $response);
    }
}
