<?php
/**
 * PHP version 7.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace Ads\CodeList\Posters\Ports;

use Ads\CodeList\Posters\Poster;
use Ads\CodeList\Posters\Posters;
use Ads\ContractTests\PostersTest;
use Ads\DataStorage\WithTableCleanup;
use Ads\DependencyInjection\WithContainer;
use Doctrine\ORM\EntityManager;

class PostersRepositoryTest extends PostersTest
{
    use WithContainer, WithTableCleanup;

    protected function posters(): Posters
    {
        $this->empty('posters');
        $manager = $this->container()->get(EntityManager::class);
        $manager
            ->createQuery('DELETE FROM ' . Poster::class)
            ->execute();
        return $this->container()->get(Posters::class);
    }
}
