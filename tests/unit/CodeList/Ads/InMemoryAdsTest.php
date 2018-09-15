<?php
/**
 * PHP version 7.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace Ads\CodeList\Ads;

use Ads\ContractTests\AdsTest;

class InMemoryAdsTest extends AdsTest
{
    public function ads(): Ads
    {
        return new InMemoryAds();
    }
}
