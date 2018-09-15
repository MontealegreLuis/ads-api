<?php
/**
 * PHP version 7.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace Ads\CodeList\Registration\SignUp;

use Ads\CodeList\Posters\Poster;
use Ads\CodeList\Posters\Posters;
use LogicException;

class SignUpPosterAction
{
    /** @var Posters */
    private $posters;

    /** @var SignUpPosterResponder */
    private $responder;

    public function __construct(Posters $posters)
    {
        $this->posters = $posters;
    }

    public function signUpPoster(SignUpPosterInput $input): void
    {
        if ($input->isValid()) {
            $this->tryToSignUpWith($input);
        } else {
            $this->responder()->respondToInvalidPosterInformation($input->errors());
        }
    }

    private function tryToSignUpWith(SignUpPosterInput $input): void
    {
        try {
            $poster = $this->signUp($input);
            $this->responder()->respondToPosterSignedUp($poster);
        } catch (UnavailableUsername $exception) {
            $this->responder()->respondToUnavailableUsername($input, $exception);
        }
    }

    private function signUp(SignUpPosterInput $input): Poster
    {
        $registeredPoster = $this->posters->withUsername($input->username());
        if ($registeredPoster) {
            throw new UnavailableUsername($input->username());
        }

        $poster = Poster::signUp($input->username(), $input->password(), $input->name(), $input->email());
        $this->posters->add($poster);

        return $poster;
    }

    public function attach(SignUpPosterResponder $responder): void
    {
        $this->responder = $responder;
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
