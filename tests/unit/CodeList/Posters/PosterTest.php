<?php
/**
 * PHP version 7.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace Ads\CodeList\Posters;

use Ads\Application\DomainEvents\DomainEventsCollector;
use Ads\Application\DomainEvents\EventPublisher;
use Ads\Builders\A;
use Ads\CodeList\Ads\Description;
use Ads\CodeList\Ads\Title;
use PHPUnit\Framework\TestCase;

class PosterTest extends TestCase
{
    /** @test */
    function it_can_sign_up()
    {
        $collector = new DomainEventsCollector();
        EventPublisher::subscribe($collector);

        $poster = Poster::signUp(
            new Username('thomas_anderson'),
            Password::fromPlainText('ilovemyjob'),
            new Name('Thomas Anderson'),
            Email::withAddress('thomas.anderson@thematrix.org')
        );

        $this->assertTrue($poster->hasUsername(new Username('thomas_anderson')));
        $this->assertCount(1, $collector->events());
        $this->assertInstanceOf(PosterHasSignedUp::class, $collector->events()[0]);
    }

    /** @test */
    function it_can_verify_her_password()
    {
        $password = 'ilovemyjob';
        $poster = Poster::signUp(
            new Username('thomas_anderson'),
            Password::fromPlainText($password),
            new Name('Thomas Anderson'),
            Email::withAddress('thomas.anderson@thematrix.org')
        );

        $this->assertTrue($poster->verifyPassword($password));
        $this->assertFalse($poster->verifyPassword('incorrect password'));
    }

    /** @test */
    function it_can_draft_a_post()
    {
        $poster = A::poster()->build();
        $now = 1537024269;

        $ad = $poster->draft(
            Title::fromText('Test title'),
            Description::fromText('Test description'),
            $now
        );

        $this->assertTrue($ad->isDraft());
    }

    /** @before @after */
    function reset()
    {
        EventPublisher::reset();
    }
}
