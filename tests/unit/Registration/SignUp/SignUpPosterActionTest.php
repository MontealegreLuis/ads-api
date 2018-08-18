<?php
/**
 * PHP version 7.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace Ads\Registration\SignUp;

use Ads\Application\DomainEvents\EventPublisher;
use Ads\Builders\A;
use Ads\Posters\DomainEventsCollector;
use Ads\Posters\InMemoryPosters;
use Ads\Posters\Poster;
use Ads\Posters\PosterHasSignedUp;
use Ads\Posters\PosterInformation;
use Ads\Posters\Posters;
use Ads\Posters\Username;
use PHPUnit\Framework\TestCase;
use Prophecy\Argument;

class SignUpPosterActionTest extends TestCase
{
    /** @var SignUpPosterAction */
    private $action;

    /** @test */
    function it_signs_up_a_poster()
    {
        $collector = new DomainEventsCollector();
        EventPublisher::subscribe($collector);

        $this->action->signUp(SignUpPosterInput::withValues([
            'username' => 'thomas_anderson',
            'password' => '12345678',
            'name' => 'Thomas Anderson',
            'email' => 'thomas.anderson@thematrix.org',
        ]));

        $this->assertNotNull($this->posters->withUsername(new Username('thomas_anderson')));
        $this->assertCount(1, $collector->events());
        $this->assertInstanceOf(PosterHasSignedUp::class, $collector->events()[0]);
        $this->responder
            ->respondToPosterSignedUp(Argument::type(Poster::class))
            ->shouldHaveBeenCalled();
    }

    /** @test */
    function it_provides_feedback_if_a_username_is_taken()
    {
        $existingUsername = 'thomas_anderson';
        $this->posters->add(A::poster()->withUsername($existingUsername)->build());
        $collector = new DomainEventsCollector();
        EventPublisher::subscribe($collector);

        $this->action->signUp(SignUpPosterInput::withValues([
            'username' => $existingUsername,
            'password' => '12345678',
            'name' => 'Thomas Anderson',
            'email' => 'thomas.anderson@thematrix.org',
        ]));

        $this->assertEmpty($collector->events());
        $this->responder
            ->respondToUnavailableUsername(
                Argument::type(PosterInformation::class),
                Argument::type(UnavailableUsername::class)
            )
            ->shouldHaveBeenCalled();
    }

    /** @test */
    function it_provides_feedback_if_any_input_is_invalid()
    {
        $collector = new DomainEventsCollector();
        EventPublisher::subscribe($collector);

        $this->action->signUp(SignUpPosterInput::withValues([
            'username' => '',
            'password' => '',
            'name' => '',
            'email' => '',
        ]));

        $this->assertEmpty($collector->events());
        $this->responder
            ->respondToInvalidPosterInformation(Argument::type('array'))
            ->shouldHaveBeenCalled();
    }

    /** @before */
    function configure()
    {
        $this->responder = $this->prophesize(SignUpPosterResponder::class);
        $this->posters = new InMemoryPosters();
        $this->action = new SignUpPosterAction(new SignUpPoster($this->posters));
        $this->action->attach($this->responder->reveal());
        EventPublisher::reset();
    }

    /** @after */
    function cleanup()
    {
        EventPublisher::reset();
    }

    /** @var Posters */
    private $posters;

    /** @var SignUpPosterResponder */
    private $responder;
}
