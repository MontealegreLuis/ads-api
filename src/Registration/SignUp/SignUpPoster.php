<?php
/**
 * PHP version 7.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace Ads\Registration\SignUp;

use Ads\Posters\Poster;
use Ads\Posters\PosterInformation;
use Ads\Posters\Posters;

class SignUpPoster
{
    /** @var Posters */
    private $posters;

    public function __construct(Posters $posters)
    {
        $this->posters = $posters;
    }

    /** @throws UnavailableUsername */
    public function signUp(PosterInformation $information): Poster
    {
        $registeredPoster = $this->posters->withUsername($information->username());
        if ($registeredPoster) {
            throw new UnavailableUsername($information->username());
        }

        $poster = Poster::signUp($information);
        $this->posters->add($poster);

        return $poster;
    }
}
