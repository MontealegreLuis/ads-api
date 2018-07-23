<?php
/**
 * PHP version 7.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace Ads\Registration\SignUp;

use Ads\Posters\PosterInformation;
use LogicException;

class SignUpPosterAction
{
    /** @var SignUpPoster */
    private $useCase;

    /** @var SignUpPosterResponder */
    private $responder;

    public function __construct(SignUpPoster $useCase)
    {
        $this->useCase = $useCase;
    }

    public function attach(SignUpPosterResponder $responder): void
    {
        $this->responder = $responder;
    }

    public function signUp(SignUpPosterInput $input): void
    {
        if ($input->isValid()) {
            $this->tryToSignUpWith($input);
        } else {
            $this->responder()->respondToInvalidPosterInformation($input->errors());
        }
    }

    private function tryToSignUpWith(SignUpPosterInput $input): void
    {
        $information = PosterInformation::fromInput($input->values());
        try {
            $poster = $this->useCase->signUp($information);
            $this->responder()->respondToPosterSignedUp($poster);
        } catch (UnavailableUsername $exception) {
            $this->responder()->respondToUnavailableUsername($information, $exception);
        }
    }

    /** @throws LogicException */
    private function responder(): SignUpPosterResponder
    {
        if (!$this->responder) {
            throw new LogicException('Cannot sign up a poster without a responder');
        }
        return $this->responder;
    }
}
