<?php
/**
 * PHP version 7.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace Ads\Ports\DomainEvents;

class EventPublisher
{
    /** @var EventPublisher */
    private static $instance;

    /** @var DomainEvent[] */
    private $events;

    /** @var EventSubscriber[] */
    private $subscribers;

    public static function instance(): EventPublisher
    {
        if (!self::$instance) {
            self::$instance = new EventPublisher();
        }
        return self::$instance;
    }

    public static function reset(): void
    {
        self::$instance = null;
    }

    public function subscribe(EventSubscriber $subscriber): void
    {
        $this->subscribers[] = $subscriber;
    }

    public function publish(DomainEvent $aDomainEvent): void
    {
        $this->events[] = $aDomainEvent;
        foreach ($this->subscribers as $aSubscriber) {
            if ($aSubscriber->isSubscribedTo($aDomainEvent)) {
                $aSubscriber->handle($aDomainEvent);
            }
        }
    }

    /** @return DomainEvent[] */
    public function events(): array
    {
        return $this->events;
    }

    private function __construct()
    {
        $this->events = [];
        $this->subscribers = [];
    }
}
