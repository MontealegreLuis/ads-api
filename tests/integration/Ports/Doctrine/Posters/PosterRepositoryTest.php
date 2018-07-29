<?php
/**
 * PHP version 7.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace Ads\Ports\Doctrine\Posters;

use Ads\ContractTests\PostersTest;
use Ads\DependencyInjection\WithContainer;
use Ads\Posters\Poster;
use Ads\Posters\Posters;
use Doctrine\ORM\EntityManager;

class PosterRepositoryTest extends PostersTest
{
    use WithContainer;

    /** @before */
    function cleanup()
    {
        $this->container()[EntityManager::class]
            ->createQuery('DELETE FROM ' . Poster::class)
            ->execute();

    }

    protected function posters(): Posters
    {
        return $this->container()[Posters::class];
    }
}
