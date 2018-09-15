<?php
/**
 * PHP version 7.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace Ads\CodeList\Ads;

class InMemoryAds implements Ads
{
    /** @var Ad[] */
    private $ads = [];

    public function add(Ad $ad): void
    {
        $this->ads[] = $ad;
    }
}
