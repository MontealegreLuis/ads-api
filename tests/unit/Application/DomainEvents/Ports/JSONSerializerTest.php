<?php
/**
 * PHP version 7.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace Ads\Application\DomainEvents\Ports;

use Ads\CodeList\Posters\Email;
use Ads\CodeList\Posters\Name;
use Ads\CodeList\Posters\PosterHasSignedUp;
use Ads\CodeList\Posters\Username;
use PHPUnit\Framework\TestCase;

class JSONSerializerTest extends TestCase
{
    /** @test */
    function it_serializes_a_domain_event()
    {
        $aDomainEvent = new PosterHasSignedUp(
            new Username('thomas_anderson'),
            new Name('Thomas Anderson'),
            Email::withAddress('thomas.anderson@thematrix.org'),
            1532820206
        );
        $serializer = new JSONSerializer();

        $aSerializedEvent = $serializer->serialize($aDomainEvent);

        $this->assertEquals('{"occurred_on":1532820206,"username":"thomas_anderson","name":"Thomas Anderson","email":"thomas.anderson@thematrix.org"}', $aSerializedEvent);
    }
}
