<?php
/**
 * PHP version 7.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace Ads\Ports\DomainEvents;

use DateTime;

abstract class DomainEvent
{
    /** @var DateTime */
    protected $occurredOn;

    public function occurredOn(): DateTime
    {
        return $this->occurredOn;
    }
}
