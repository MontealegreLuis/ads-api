<?php
/**
 * PHP version 7.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace Ads\Application\Registration;

use Ads\Posters\PosterInformation;
use Ads\Registration\UnavailableUsername;

interface CanSignUpPosters
{
    public function respondToInvalidPosterInformation(array $errors): void;

    public function respondToPosterSignedUp(PosterInformation $poster): void;

    public function respondToUnavailableUsername(PosterInformation $information, UnavailableUsername $exception): void;
}
