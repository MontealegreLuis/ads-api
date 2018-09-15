<?php
/**
 * PHP version 7.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace Ads\ContractTests;

use Ads\Builders\A;
use Ads\CodeList\Ads\Ads;
use Ads\CodeList\Ads\UnknownAd;
use PHPUnit\Framework\TestCase;

abstract class AdsTest extends TestCase
{
    abstract public function ads(): Ads;

    /** @test */
    function it_finds_an_exsiting_ad()
    {
        $ads = $this->ads();
        $existingAd = A::ad()->build();
        $ads->add($existingAd);

        $savedAd = $ads->with($existingAd->id());

        $this->assertEquals($savedAd, $existingAd);
    }

    /** @test */
    function it_fails_to_find_an_unknown_ad()
    {
        $this->expectException(UnknownAd::class);
        $this->ads()->with(1);
    }
}
