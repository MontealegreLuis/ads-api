<?php
/**
 * PHP version 7.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace Ads\CodeList\Posters;

use Ads\Application\DomainEvents\DomainEventsCollector;
use Ads\Application\DomainEvents\EventPublisher;
use Ads\CodeList\Registration\SignUp\SignUpPosterInput;
use PHPUnit\Framework\TestCase;

class PosterTest extends TestCase
{
    /** @test */
    function it_can_sign_up()
    {
        $collector = new DomainEventsCollector();
        EventPublisher::subscribe($collector);

        $poster = Poster::signUp(PosterInformation::fromInput(SignUpPosterInput::withValues([
            'username' => 'thomas_anderson',
            'password' => 'ilovemyjob',
            'name' => 'Thomas Anderson',
            'email' => 'thomas.anderson@thematrix.org'
        ])));

        $this->assertTrue($poster->hasUsername(new Username('thomas_anderson')));
        $this->assertCount(1, $collector->events());
        $this->assertInstanceOf(PosterHasSignedUp::class, $collector->events()[0]);
    }

    /** @test */
    function it_can_verify_her_password()
    {
        $password = 'ilovemyjob';
        $poster = Poster::signUp(PosterInformation::fromInput(SignUpPosterInput::withValues([
            'username' => 'thomas_anderson',
            'password' => $password,
            'name' => 'Thomas Anderson',
            'email' => 'thomas.anderson@thematrix.org'
        ])));

        $this->assertTrue($poster->verifyPassword($password));
        $this->assertFalse($poster->verifyPassword('incorrect password'));
    }

    /** @before @after */
    function reset()
    {
        EventPublisher::reset();
    }
}
