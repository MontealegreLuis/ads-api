<?php
/**
 * PHP version 7.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace Ads\Posters;

use Ads\Ports\DomainEvents\EventPublisher;
use PHPUnit\Framework\TestCase;

class PosterTest extends TestCase
{
    /** @before */
    function reset()
    {
        EventPublisher::reset();
    }

    /** @test */
    function it_can_sign_up()
    {
        Poster::signUp(PosterInformation::fromInput([
            'username' => 'thomas_anderson',
            'password' => 'ilovemyjob',
            'name' => 'Thomas Anderson',
            'email' => 'thomas.anderson@thematrix.org'
        ]));

        $this->assertCount(1, EventPublisher::instance()->events());
        $this->assertInstanceOf(PosterHasSignedUp::class, EventPublisher::instance()->events()[0]);
    }
}
