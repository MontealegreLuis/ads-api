<?php
/**
 * PHP version 7.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace Ads\CodeList\Posters;

use Ads\Application\DomainEvents\DomainEventsCollector;
use Ads\Application\DomainEvents\EventPublisher;
use Ads\CodeList\Ads\AdInformation;
use Ads\CodeList\Listings\DraftAd\DraftAdInput;
use Ads\CodeList\Registration\SignUp\SignUpPosterInput;
use PHPUnit\Framework\TestCase;

class PosterTest extends TestCase
{
    /** @test */
    function it_can_sign_up()
    {
        $collector = new DomainEventsCollector();
        EventPublisher::subscribe($collector);

        $input = SignUpPosterInput::withValues([
            'username' => 'thomas_anderson',
            'password' => 'ilovemyjob',
            'name' => 'Thomas Anderson',
            'email' => 'thomas.anderson@thematrix.org'
        ]);
        $poster = Poster::signUp($input->username(), $input->password(), $input->name(), $input->email());

        $this->assertTrue($poster->hasUsername(new Username('thomas_anderson')));
        $this->assertCount(1, $collector->events());
        $this->assertInstanceOf(PosterHasSignedUp::class, $collector->events()[0]);
    }

    /** @test */
    function it_can_verify_her_password()
    {
        $password = 'ilovemyjob';
        $input = SignUpPosterInput::withValues([
            'username' => 'thomas_anderson',
            'password' => $password,
            'name' => 'Thomas Anderson',
            'email' => 'thomas.anderson@thematrix.org'
        ]);
        $poster = Poster::signUp($input->username(), $input->password(), $input->name(), $input->email());

        $this->assertTrue($poster->verifyPassword($password));
        $this->assertFalse($poster->verifyPassword('incorrect password'));
    }

    /** @test */
    function it_can_draft_a_post()
    {
        $input = SignUpPosterInput::withValues([
            'username' => 'thomas_anderson',
            'password' => 'ilovemyjob',
            'name' => 'Thomas Anderson',
            'email' => 'thomas.anderson@thematrix.org'
        ]);
        $poster = Poster::signUp($input->username(), $input->password(), $input->name(), $input->email());
        $input = DraftAdInput::withValues([
            'title' => 'Test title',
            'description' => 'Test description',
        ]);

        $ad = $poster->draft($input->title(), $input->description(), $input->createdAt());

        $this->assertTrue($ad->isDraft());
    }

    /** @before @after */
    function reset()
    {
        EventPublisher::reset();
    }
}
