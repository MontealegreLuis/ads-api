<?php
/**
 * PHP version 7.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace Ads\Builders;

class A
{
    public static function poster(): PosterBuilder
    {
        return new PosterBuilder();
    }
}