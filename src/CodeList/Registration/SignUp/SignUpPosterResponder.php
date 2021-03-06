<?php
/**
 * PHP version 7.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace Ads\CodeList\Registration\SignUp;

use Ads\CodeList\Posters\Poster;
use Ads\CodeList\Posters\PosterInformation;

interface SignUpPosterResponder
{
    public function respondToInvalidPosterInformation(array $errors): void;

    public function respondToPosterSignedUp(Poster $poster): void;

    public function respondToUnavailableUsername(SignUpPosterInput $input, UnavailableUsername $error): void;
}
