<?php
/**
 * PHP version 7.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace Ads\CodeList\Listings;

use Ads\CodeList\Posters\Username;
use RuntimeException;

class UnknownPoster extends RuntimeException
{
    public static function withUsername(Username $username): UnknownPoster
    {
        return new UnknownPoster("Cannot find poster with username '$username'");
    }
}
