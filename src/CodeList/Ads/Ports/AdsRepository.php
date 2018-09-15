<?php
/**
 * PHP version 7.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace Ads\CodeList\Ads\Ports;


use Ads\Application\DataStorage\Repository;
use Ads\CodeList\Ads\Ad;
use Ads\CodeList\Ads\Ads;
use Ads\CodeList\Ads\UnknownAd;

class AdsRepository extends Repository implements Ads
{
    /**
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function add(Ad $ad): void
    {
        $this->manager->persist($ad);
        if (!$this->manager->getConnection()->isTransactionActive()) {
            $this->manager->flush($ad);
        }
    }

    /** @throws UnknownAd When the ad cannot be found */
    public function with(int $id): Ad
    {
        $builder = $this->manager->createQueryBuilder();
        $builder->select('a')
            ->from(Ad::class, 'a')
            ->where('a.id = :id')
            ->setParameter('id', $id);

        $ad = $builder->getQuery()->getOneOrNullResult();

        if (!$ad) {
            throw UnknownAd::withId($id);
        }
        return $ad;
    }
}
