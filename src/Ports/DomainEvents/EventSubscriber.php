<?php
/**
 * PHP version 7.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace Ads\Ports\DomainEvents;

interface EventSubscriber
{
    public function isSubscribedTo(DomainEvent $event): bool;

    public function handle(DomainEvent $event): void;
}
