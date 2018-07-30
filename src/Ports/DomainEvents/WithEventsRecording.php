<?php
/**
 * PHP version 7.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace Ads\Ports\DomainEvents;

trait WithEventsRecording
{
    public function recordThat(DomainEvent $event): void
    {
        EventPublisher::publish($event);
    }
}
