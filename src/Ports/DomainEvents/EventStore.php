<?php
/**
 * PHP version 7.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace Ads\Ports\DomainEvents;

use Ads\Ports\Pagination\Page;
use Ads\Ports\Pagination\Paginator;

interface EventStore
{
    public function append(StoredEvent $aDomainEvent): void;

    public function eventsIn(Page $page): Paginator;
}
