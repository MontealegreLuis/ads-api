<?php
/**
 * PHP version 7.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace Ads\Ports\Doctrine\DomainEvents;


use Ads\Ports\Doctrine\Repository;
use Ads\Ports\DomainEvents\EventStore;
use Ads\Ports\DomainEvents\StoredEvent;
use Ads\Ports\Pagerfanta\RepositoryPaginator;
use Ads\Ports\Pagination\Page;
use Ads\Ports\Pagination\Paginator;

class EventStoreRepository extends Repository implements EventStore
{
    /**
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function append(StoredEvent $aDomainEvent): void
    {
        $this->manager->persist($aDomainEvent);
        $this->manager->flush($aDomainEvent);
    }

    /** @throws \Ads\Ports\Pagination\InvalidPage If the requested page is greater than the total number of pages */
    public function eventsIn(Page $page): Paginator
    {
        $builder = $this->manager->createQueryBuilder()
            ->select('e')
            ->from(StoredEvent::class, 'e')
            ->orderBy('e.occurredOn', 'ASC');

        $paginator = RepositoryPaginator::from($builder);
        $paginator->setPage($page);

        return $paginator;
    }
}
