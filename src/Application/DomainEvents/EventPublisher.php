<?php
/**
 * PHP version 7.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace Ads\Application\DomainEvents;

class EventPublisher
{
    /** @var EventSubscriber[] */
    private static $subscribers = [];

    public static function reset(): void
    {
        self::$subscribers = [];
    }

    public static function subscribe(EventSubscriber $subscriber): void
    {
        self::$subscribers[] = $subscriber;
    }

    public static function publish(DomainEvent $aDomainEvent): void
    {
        foreach (self::$subscribers as $subscriber) {
            if ($subscriber->isSubscribedTo($aDomainEvent)) {
                $subscriber->handle($aDomainEvent);
            }
        }
    }
}
