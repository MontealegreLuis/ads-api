<?php
/**
 * PHP version 7.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace Ads\Application\DomainEvents;

use Ads\Posters\Email;
use Ads\Posters\Name;
use Ads\Posters\PosterHasSignedUp;
use Ads\Posters\Username;
use PHPUnit\Framework\TestCase;

class EventPublisherTest extends TestCase
{
    /** @test */
    function it_notifies_subscribers_of_an_event()
    {
        $event = new PosterHasSignedUp(
            new Username('thomas_anderson'),
            new Name('Thomas Anderson'),
            Email::withAddress('thomas.anderson@thematrix.org')
        );

        /** @var EventSubscriber $subscriber */
        $subscriber = $this->prophesize(EventSubscriber::class);
        $subscriber->isSubscribedTo($event)->willReturn(true);
        $subscriber->handle($event)->shouldBeCalled();
        EventPublisher::subscribe($subscriber->reveal());

        EventPublisher::publish($event);
    }

    /** @test */
    function it_does_not_notifies_subscribers_not_subscribed_to_an_event()
    {
        $event = new PosterHasSignedUp(
            new Username('thomas_anderson'),
            new Name('Thomas Anderson'),
            Email::withAddress('thomas.anderson@thematrix.org')
        );

        /** @var EventSubscriber $subscriber */
        $subscriber = $this->prophesize(EventSubscriber::class);
        $subscriber->isSubscribedTo($event)->willReturn(false);
        $subscriber->handle($event)->shouldNotBeCalled();
        EventPublisher::subscribe($subscriber->reveal());

        EventPublisher::publish($event);
    }

    /** @before @after */
    function configure()
    {
        EventPublisher::reset();
    }
}
