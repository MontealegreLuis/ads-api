<?php
/**
 * PHP version 7.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace Ads\CodeList\Posters;

use Ads\Application\DomainEvents\RecordsEvents;
use Ads\Application\DomainEvents\WithEventsRecording;

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

    public static function signUp(PosterInformation $information): Poster
    {
        return new Poster($information);
    }

    private function __construct(PosterInformation $information)
    {
        $this->username = $information->username();
        $this->password = $information->password();
        $this->name = $information->name();
        $this->email = $information->email();

        $this->recordThat(new PosterHasSignedUp(
            $this->username,
            $this->name,
            $this->email
        ));
    }

    public function hasUsername(Username $username): bool
    {
        return (string)$this->username === (string)$username;
    }

    public function username(): Username
    {
        return $this->username;
    }
}
