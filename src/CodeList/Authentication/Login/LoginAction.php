<?php
/**
 * PHP version 7.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace Ads\CodeList\Authentication\Login;

use Ads\CodeList\Posters\Poster;
use Ads\CodeList\Posters\Posters;
use LogicException;

class LoginAction
{
    /** @var LoginResponder */
    private $responder;

    /** @var Posters */
    private $posters;

    public function __construct(Posters $posters)
    {
        $this->posters = $posters;
    }

    public function authenticatePoster(LoginInput $input): void
    {
        if ($input->isValid()) {
            $this->attemptLogin($input);
        } else {
            $this->responder()->respondToInvalidInput($input->errors());
        }
    }

    private function attemptLogin(LoginInput $input): void
    {
        /** @var Poster $poster */
        $poster = $this->posters->withUsername($input->username());
        if (!$poster) {
            $this->responder()->respondToUserNotFound($input);
            return;
        }

        if (!$poster->verifyPassword($input->plainTextPassword())) {
            $this->responder()->respondToIncorrectPassword($input);
            return;
        }

        $this->responder()->respondToSuccessfulAuthentication($poster);
    }

    public function attach(LoginResponder $responder): void
    {
        $this->responder = $responder;
    }

    /** @throws LogicException If no responder has been attached to this action */
    private function responder(): LoginResponder
    {
        if (!$this->responder) {
            throw new LogicException('Cannot login a user without a responder');
        }
        return $this->responder;
    }
}
