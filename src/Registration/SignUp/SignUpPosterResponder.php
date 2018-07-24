<?php
/**
 * PHP version 7.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace Ads\Registration\SignUp;

use Ads\Posters\Poster;
use Ads\Posters\PosterInformation;

interface SignUpPosterResponder
{
    public function respondToInvalidPosterInformation(array $errors): void;

    public function respondToPosterSignedUp(Poster $poster): void;

    public function respondToUnavailableUsername(PosterInformation $information, UnavailableUsername $error): void;
}
