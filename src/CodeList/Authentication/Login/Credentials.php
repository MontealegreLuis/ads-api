<?php
/**
 * PHP version 7.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace Ads\CodeList\Authentication\Login;

use Ads\CodeList\Posters\Username;

class Credentials
{
    /** @var Username */
    private $username;

    /** @var string */
    private $plainTextPassword;

    public static function from(array $values): Credentials
    {
        return new Credentials($values);
    }

    public function username(): Username
    {
        return $this->username;
    }

    public function plainTextPassword(): string
    {
        return $this->plainTextPassword;
    }

    private function __construct(array $values)
    {
        $this->username = new Username($values['username']);
        $this->plainTextPassword = $values['password'];
    }
}
