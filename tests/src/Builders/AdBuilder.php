<?php
/**
 * PHP version 7.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace Ads\Builders;

use Ads\CodeList\Ads\Ad;
use Ads\CodeList\Ads\Description;
use Ads\CodeList\Ads\Title;

class AdBuilder
{
    use WithFakeData;

    public function build(): Ad
    {
        return Ad::draft(
            Title::fromText($this->faker->sentence(3)),
            Description::fromText($this->faker->paragraph()),
            $this->faker->dateTime->getTimestamp(),
            A::poster()->build()
        );
    }
}
