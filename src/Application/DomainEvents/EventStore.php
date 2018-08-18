<?php
/**
 * PHP version 7.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace Ads\Application\DomainEvents;

use Ads\Application\Pagination\Page;
use Ads\Application\Pagination\Paginator;

interface EventStore
{
    public function append(StoredEvent $aDomainEvent): void;

    public function eventsIn(Page $page): Paginator;
}
