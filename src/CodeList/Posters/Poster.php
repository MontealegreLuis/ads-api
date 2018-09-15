<?php
/**
 * PHP version 7.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace Ads\CodeList\Posters;

use Ads\Application\DomainEvents\RecordsEvents;
use Ads\Application\DomainEvents\WithEventsRecording;
use Ads\CodeList\Ads\Ad;
use Ads\CodeList\Ads\Description;
use Ads\CodeList\Ads\Title;

/**
 * A poster is a registered user who can post ads in the system
 *
 * @see PosterTest
 */
class Poster implements RecordsEvents
{
    use WithEventsRecording;

    /** @var Username */
    private $username;

    /** @var Password */
    private $password;

    /** @var Name */
    private $name;

    /** @var Email */
    private $email;

    public static function signUp(Username $username, Password $password, Name $name, Email $email): Poster
    {
        $poster = new Poster($username, $password, $name, $email);
        $poster->recordThat(new PosterHasSignedUp(
            $poster->username,
            $poster->name,
            $poster->email
        ));
        return $poster;
    }

    public function hasUsername(Username $username): bool
    {
        return (string)$this->username === (string)$username;
    }

    public function username(): Username
    {
        return $this->username;
    }

    public function verifyPassword(string $plainTextPassword): bool
    {
        return $this->password->verify($plainTextPassword);
    }

    public function draft(Title $title, Description $description, int $createdAt): Ad
    {
        return Ad::draft($title, $description, $createdAt, $this);
    }

    protected function __construct(Username $username, Password $password, Name $name, Email $email)
    {
        $this->username = $username;
        $this->password = $password;
        $this->name = $name;
        $this->email = $email;
    }
}
