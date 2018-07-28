<?php
/**
 * PHP version 7.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace Ads\Posters;

use Ads\Ports\DomainEvents\DomainEvent;
use Carbon\Carbon;
use DateTime;

class PosterHasSignedUp extends DomainEvent
{
    /** @var Username */
    private $username;

    /** @var Name */
    private $name;

    /** @var Email */
    private $email;

    public function __construct(Username $username, Name $name, Email $email, int $occurredOn = 0)
    {
        $this->username = $username;
        $this->name = $name;
        $this->email = $email;
        $this->occurredOn = $occurredOn ?? Carbon::now('UTC')->getTimestamp();
    }
}
