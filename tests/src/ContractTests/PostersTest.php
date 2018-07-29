<?php
/**
 * PHP version 7.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace Ads\ContractTests;

use Ads\Builders\A;
use Ads\Ports\DomainEvents\EventPublisher;
use Ads\Posters\Posters;
use Ads\Posters\Username;
use PHPUnit\Framework\TestCase;

abstract class PostersTest extends TestCase
{
    /** @before */
    function cleanup()
    {
        EventPublisher::reset();
    }

    /** @test */
    function it_finds_an_existing_poster_by_its_username()
    {
        $posters = $this->posters();

        $existingPoster = A::poster()->withUsername('elliot_alderson')->build();
        $posters->add($existingPoster);

        $foundPoster = $posters->withUsername(new Username('elliot_alderson'));

        $this->assertEquals($existingPoster, $foundPoster);
    }

    /** @test */
    function it_does_not_find_a_poster_by_username_if_it_has_not_been_registed_previously()
    {
        $posters = $this->posters();

        $noPoster = $posters->withUsername(new Username('unregister_username'));

        $this->assertNull($noPoster);
    }

    abstract protected function posters(): Posters;
}
