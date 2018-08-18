<?php
/**
 * PHP version 7.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace Ads\Application\DomainEvents\Ports;

use Ads\Application\DomainEvents\EventStore;
use Ads\Application\DomainEvents\StoredEvent;
use Ads\Application\Pagination\Page;
use Ads\Application\Pagination\Paginator;
use Ads\Application\Pagination\Ports\RepositoryPaginator;
use Ads\Ports\Doctrine\Repository;

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

    /** @throws \Ads\Application\Pagination\InvalidPage If the requested page is greater than the total number of pages */
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
