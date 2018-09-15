<?php
/**
 * PHP version 7.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace Ads\CodeList\Listings\DraftAd;

use Ads\CodeList\Ads\Ads;
use Ads\CodeList\Listings\UnknownPoster;
use Ads\CodeList\Posters\Posters;
use Ads\CodeList\Posters\Username;
use LogicException;

class DraftAdAction
{
    /** @var Posters */
    private $posters;

    /** @var Ads */
    private $ads;

    /** @var DraftAdResponder */
    private $responder;

    public function __construct(Posters $posters, Ads $ads)
    {
        $this->posters = $posters;
        $this->ads = $ads;
    }

    /** @throws UnknownPoster If ad author cannot be found */
    public function draftAd(DraftAdInput $input): void
    {
        if ($input->isValid()) {
            $this->tryToDraftAd($input);
        } else {
            $this->responder()->respondToInvalidInput($input);
        }
    }

    /** @throws UnknownPoster If ad author cannot be found */
    private function tryToDraftAd(DraftAdInput $input): void
    {
        $username = new Username($input->author());
        $poster = $this->posters->withUsername($username);
        if (!$poster) {
            $this->responder()->respondToUnknownPosterWith($username);
            return;
        }

        $ad = $poster->draft($input->title(), $input->description(), $input->createdAt());
        $this->ads->add($ad);
        $this->responder()->respondToAdDrafted($ad);
    }

    public function attach(DraftAdResponder $responder): void
    {
        $this->responder = $responder;
    }

    private function responder(): DraftAdResponder
    {
        if (!$this->responder) {
            throw new LogicException('Cannot draft an ad without a responder');
        }
        return $this->responder;
    }
}
