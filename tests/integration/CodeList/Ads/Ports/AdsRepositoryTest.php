<?php
/**
 * PHP version 7.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace Ads\CodeList\Ads\Ports;

use Ads\CodeList\Ads\Ads;
use Ads\ContractTests\AdsTest;
use Ads\DataStorage\WithTableCleanup;
use Ads\DependencyInjection\WithContainer;

class AdsRepositoryTest extends AdsTest
{
    use WithContainer, WithTableCleanup;

    public function ads(): Ads
    {
        $this->empty('ads');
        return $this->container()->get(Ads::class);
    }
}
