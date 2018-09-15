<?php
/**
 * PHP version 7.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace Ads\CodeList\Authentication\Login;

use Ads\CodeList\Posters\Poster;

interface LoginResponder
{
    public function respondToInvalidInput(array $errors): void;

    public function respondToUserNotFound(LoginInput $input): void;

    public function respondToIncorrectPassword(LoginInput $input): void;

    public function respondToSuccessfulAuthentication(Poster $poster): void;
}
