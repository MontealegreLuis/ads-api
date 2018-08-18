<?php
/**
 * PHP version 7.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace Ads\Ports\DomainEvents;

use Ads\Application\DomainEvents\Ports\JSONSerializer;
use Ads\Application\DomainEvents\StoredEventFactory;
use Ads\Posters\Email;
use Ads\Posters\Name;
use Ads\Posters\PosterHasSignedUp;
use Ads\Posters\Username;
use PHPUnit\Framework\TestCase;

class StoredEventFactoryTest extends TestCase
{
    /** @test */
    function it_creates_an_stored_event_from_a_domain_event()
    {
        $factory = new StoredEventFactory(new JSONSerializer());
        $aDomainEvent = new PosterHasSignedUp(
            new Username('thomas_anderson'),
            new Name('Thomas Anderson'),
            Email::withAddress('thomas.anderson@thematrix.org'),
            1532820206
        );

        $storedEvent = $factory->from($aDomainEvent);

        $this->assertEquals(PosterHasSignedUp::class, $storedEvent->type());
        $this->assertEquals('{"occurred_on":1532820206,"username":"thomas_anderson","name":"Thomas Anderson","email":"thomas.anderson@thematrix.org"}', $storedEvent->body());
        $this->assertEquals(1532820206, $storedEvent->occurredOn());
    }
}
