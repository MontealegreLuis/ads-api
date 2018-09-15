<?php
/**
 * PHP version 7.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace Ads\CodeList\Listings\DraftAd;

use Ads\Builders\A;
use Ads\CodeList\Ads\Ad;
use Ads\CodeList\Ads\InMemoryAds;
use Ads\CodeList\Posters\InMemoryPosters;
use Ads\CodeList\Posters\Username;
use LogicException;
use PHPUnit\Framework\TestCase;
use Prophecy\Argument;

class DraftAdActionTest extends TestCase
{
    /** @test */
    function it_cannot_draft_an_ad_without_a_responder()
    {
        $this->expectException(LogicException::class);
        $this->action->draftAction(DraftAdInput::withValues([]));
    }

    /** @test */
    function it_cannot_draft_an_ad_if_poster_cannot_be_found()
    {
        $input = DraftAdInput::withValues([
            'title' => 'Test title',
            'description' => 'Test description',
            'author' => 'elliot_alderson',
        ]);
        $this->action->attach($this->responder->reveal());

        $this->action->draftAction($input);

        $this->responder->respondToUnknownPosterWith(Argument::type(Username::class))->shouldHaveBeenCalled();
        $this->assertTrue($input->isValid());
        $this->assertEmpty($input->errors());
    }

    /** @test */
    function it_responds_to_invalid_input()
    {
        $input = DraftAdInput::withValues([]);
        $this->action->attach($this->responder->reveal());

        $this->action->draftAction($input);

        $this->responder->respondToInvalidInput($input)->shouldHaveBeenCalled();
        $this->assertFalse($input->isValid());
        $this->assertCount(2, $input->errors());
    }

    /** @test */
    function it_drafts_a_new_ad()
    {
        $poster = A::poster()->withUsername('elliot_alderson')->build();
        $this->posters->add($poster);
        $input = DraftAdInput::withValues([
            'title' => 'Test title',
            'description' => 'Test description',
            'author' => 'elliot_alderson',
        ]);
        $this->action->attach($this->responder->reveal());

        $this->action->draftAction($input);

        $this->responder->respondToAdDrafted(Argument::type(Ad::class))->shouldHaveBeenCalled();
        $this->assertTrue($input->isValid());
        $this->assertEmpty($input->errors());
    }

    /** @before */
    function configure()
    {
        $this->posters = new InMemoryPosters();
        $this->action = new DraftAdAction($this->posters, new InMeMoryAds());
        $this->responder = $this->prophesize(DraftAdResponder::class);
    }

    /** @var DraftAdAction */
    private $action;

    /** @var \Ads\CodeList\Posters\Posters */
    private $posters;

    /** @var DraftAdResponder */
    private $responder;
}
