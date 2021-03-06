<?php
/**
 * PHP version 7.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace Ads\CodeList\Ads;

interface Ads
{
    public function add(Ad $ad): void;

    /** @throws UnknownAd When the ad cannot be found */
    public function with(int $id): Ad;
}
