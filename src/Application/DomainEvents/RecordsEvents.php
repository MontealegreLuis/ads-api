<?php
/**
 * PHP version 7.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace Ads\Application\DomainEvents;

interface RecordsEvents
{
    public function recordThat(DomainEvent $event): void;
}
