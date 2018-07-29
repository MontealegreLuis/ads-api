<?php
/**
 * PHP version 7.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace Ads\Ports\Doctrine\DomainEvents;

use Ads\Builders\A;
use Ads\DependencyInjection\WithContainer;
use Ads\Ports\DomainEvents\StoredEvent;
use Ads\Ports\DomainEvents\StoredEventFactory;
use Ads\Ports\JmsSerializer\JSONSerializer;
use Ads\Ports\Pagination\Page;
use Doctrine\ORM\EntityManager;
use PHPUnit\Framework\TestCase;

class EventStoreRepositoryTest extends TestCase
{
    use WithContainer;

    /** @var EventStoreRepository */
    private $store;

    /** @test */
    function it_can_get_a_paged_result_of_stored_events()
    {
        $now = 1532820206;
        $factory = new StoredEventFactory(new JSONSerializer());
        $eventA = $factory->from(A::posterHasSignedUpEvent()->occurredOn($now + 3)->build());
        $eventB = $factory->from(A::posterHasSignedUpEvent()->occurredOn($now + 4)->build());
        $resultsInPage2 = [$eventA, $eventB];

        $this->store->append($factory->from(A::posterHasSignedUpEvent()->occurredOn($now + 1)->build()));
        $this->store->append($factory->from(A::posterHasSignedUpEvent()->occurredOn($now + 2)->build()));
        $this->store->append($eventA);
        $this->store->append($eventB);
        $this->store->append($factory->from(A::posterHasSignedUpEvent()->occurredOn($now + 5)->build()));
        $this->store->append($factory->from(A::posterHasSignedUpEvent()->occurredOn($now + 6)->build()));

        $paginator = $this->store->eventsIn(new Page(2, 2));

        $this->assertCount(2, $paginator->pageResults());
        $this->assertEquals($resultsInPage2, $paginator->pageResults());
        $this->assertEquals(3, $paginator->lastPage());
    }

    /** @before */
    function configure()
    {
        $this->container()[EntityManager::class]
            ->createQuery('DELETE FROM ' . StoredEvent::class)
            ->execute();
        $this->store = $this->container()[EventStoreRepository::class];
    }
}
