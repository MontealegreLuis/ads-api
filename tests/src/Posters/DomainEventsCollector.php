<?php
/**
 * PHP version 7.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace Ads\Posters;

use Ads\Application\DomainEvents\DomainEvent;
use Ads\Application\DomainEvents\EventSubscriber;

class DomainEventsCollector implements EventSubscriber
{
    /** @var DomainEvent[] */
    private $events = [];

    /** @return DomainEvent[] */
    public function events(): array
    {
        return $this->events;
    }

    public function isSubscribedTo(DomainEvent $anEvent): bool
    {
        return true;
    }

    public function handle(DomainEvent $event): void
    {
        $this->events[] = $event;
    }
}
