<?php
/**
 * PHP version 7.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace Ads\Ports\Doctrine\Posters;

use Ads\Posters\Poster;
use Ads\Posters\Posters;
use Ads\Posters\Username;
use Doctrine\ORM\EntityManager;

class PosterRepository implements Posters
{
    /** @var EntityManager */
    private $manager;

    public function __construct(EntityManager $manager)
    {
        $this->manager = $manager;
    }

    /** @throws \Doctrine\ORM\NonUniqueResultException */
    public function withUsername(Username $username): ?Poster
    {
        $builder = $this->manager->createQueryBuilder()
            ->select('p')
            ->from(Poster::class, 'p')
            ->where('p.username = :username')
            ->setParameter('username', (string)$username);
        return $builder->getQuery()->getOneOrNullResult();
    }

    /** @throws \Doctrine\ORM\ORMException */
    public function add(Poster $poster): void
    {
        $this->manager->persist($poster);
        $this->manager->flush($poster);
    }
}
