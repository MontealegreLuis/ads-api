<?php
/**
 * PHP version 7.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace Ads\CodeList\Ads\Ports;

use Ads\CodeList\Ads\Ad;
use Ads\CodeList\Ads\Ads;
use Ads\ContractTests\AdsTest;
use Ads\DependencyInjection\WithContainer;
use Doctrine\ORM\EntityManager;

class AdsRepositoryTest extends AdsTest
{
    use WithContainer;

    public function ads(): Ads
    {
        $manager = $this->container()->get(EntityManager::class);
        $manager
            ->createQuery('DELETE FROM ' . Ad::class)
            ->execute();
        return new AdsRepository($manager);
    }
}
