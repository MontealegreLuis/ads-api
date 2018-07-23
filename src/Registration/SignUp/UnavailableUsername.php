<?php
/**
 * PHP version 7.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace Ads\Registration\SignUp;

use Ads\Posters\Username;
use RuntimeException;

class UnavailableUsername extends RuntimeException
{
    public function __construct(Username $username)
    {
        parent::__construct(sprintf("Username {$username} already taken"));
    }
}
