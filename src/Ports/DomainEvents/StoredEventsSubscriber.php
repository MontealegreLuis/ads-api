<?php
/**
 * PHP version 7.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace Ads\Ports\DomainEvents;

class StoredEventsSubscriber implements EventSubscriber
{
    /** @var EventStore */
    private $store;

    /** @var StoredEventFactory */
    private $factory;

    public function __construct(EventStore $store, StoredEventFactory $factory)
    {
        $this->store = $store;
        $this->factory = $factory;
    }

    public function isSubscribedTo(DomainEvent $anEvent): bool
    {
        return true;
    }

    public function handle(DomainEvent $event): void
    {
        $this->store->append($this->factory->from($event));
    }
}
