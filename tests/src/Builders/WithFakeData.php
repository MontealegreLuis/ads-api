<?php
/**
 * PHP version 7.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace Ads\Builders;

use Faker\Factory;

trait WithFakeData
{
    /** @var \Faker\Generator */
    protected $faker;

    public function __construct()
    {
        $this->faker = Factory::create();
    }
}
