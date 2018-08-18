<?php
/**
 * PHP version 7.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace Ads\CodeList\Posters;

class PosterInformation
{
    /** @var Username  */
    private $username;

    /** @var Password */
    private $password;

    /** @var Name */
    private $name;

    /** @var Email */
    private $email;

    public static function fromInput(array $validInput): PosterInformation
    {
        return new PosterInformation($validInput);
    }

    public function username(): Username
    {
        return $this->username;
    }

    public function password(): Password
    {
        return $this->password;
    }

    public function name(): Name
    {
        return $this->name;
    }

    public function email(): Email
    {
        return $this->email;
    }

    private function __construct(array $validInput)
    {
        $this->username = new Username($validInput['username']);
        $this->password = Password::fromPlainText($validInput['password']);
        $this->name = new Name($validInput['name']);
        $this->email = Email::withAddress($validInput['email']);
    }
}
