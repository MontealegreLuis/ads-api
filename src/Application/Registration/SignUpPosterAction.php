<?php
/**
 * PHP version 7.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace Ads\Application\Registration;

use Ads\Posters\PosterInformation;
use Ads\Registration\SignUpPoster;
use Ads\Registration\UnavailableUsername;

class SignUpPosterAction
{
    /** @var SignUpPoster */
    private $action;

    /** @var CanSignUpPosters */
    private $responder;

    public function __construct(SignUpPoster $action, CanSignUpPosters $responder)
    {
        $this->action = $action;
        $this->responder = $responder;
    }

    public function signUp(SignUpPosterInput $input): void
    {
        if ($input->isValid()) {
            $information = PosterInformation::fromInput($input->values());
            $this->tryToSignUpWith($information);
            $this->responder->respondToPosterSignedUp($information);
        } else {
            $this->responder->respondToInvalidPosterInformation($input->errors());
        }
    }

    private function tryToSignUpWith(PosterInformation $information): void
    {
        try {
            $this->action->signUp($information);
        } catch (UnavailableUsername $exception) {
            $this->responder->respondToUnavailableUsername($information, $exception);
        }
    }
}
