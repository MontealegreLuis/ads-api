<?php
/**
 * PHP version 7.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace Ads\Application\DomainEvents;

class StoredEvent
{
    /** @var int */
    private $eventId;

    /** @var string */
    private $body;

    /** @var string */
    private $type;

    /** @var int */
    private $occurredOn;

    public function __construct(string $body, string $type, int $occurredOn)
    {
        $this->body = $body;
        $this->type = $type;
        $this->occurredOn = $occurredOn;
    }

    public function id(): int
    {
        return $this->eventId;
    }

    public function body(): string
    {
        return $this->body;
    }

    public function type(): string
    {
        return $this->type;
    }

    public function occurredOn(): int
    {
        return $this->occurredOn;
    }
}
