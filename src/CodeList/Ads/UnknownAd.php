<?php
/**
 * PHP version 7.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace Ads\CodeList\Ads;

use RuntimeException;

class UnknownAd extends RuntimeException
{
    public static function withId(int $id): UnknownAd
    {
        return new UnknownAd("Cannot found ad with ID '$id'");
    }
}
