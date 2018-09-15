<?php
/**
 * PHP version 7.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace Ads\CodeList\Ads;

use Ads\Builders\WithFakeData;
use ReflectionClass;

class InMemoryAds implements Ads
{
    use WithFakeData;

    /** @var Ad[] */
    private $ads = [];

    /**
     * @throws \ReflectionException
     */
    public function add(Ad $ad): void
    {
        $reflectionClass = new ReflectionClass(Ad::class);
        $reflectionProperty = $reflectionClass->getProperty('id');
        $reflectionProperty->setAccessible(true);
        $reflectionProperty->setValue($ad, $this->faker->numberBetween(1, 2000));

        $this->ads[$ad->id()] = $ad;
    }

    /** @throws UnknownAd When the ad cannot be found */
    public function with(int $id): Ad
    {
        if (!isset($this->ads[$id])) {
            throw UnknownAd::withId($id);
        }
        return $this->ads[$id];
    }
}
