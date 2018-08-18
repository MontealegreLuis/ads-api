<?php
/**
 * PHP version 7.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace Ads\Application\DataStorage;

use Ads\Ports\Doctrine\Types\UsernameType;
use Doctrine\DBAL\Types\Type;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Tools\Setup;

trait EntityManagerFactory
{
    /** @var EntityManager */
    private $entityManager;

    /**
     * @throws \Doctrine\DBAL\DBALException
     * @throws \Doctrine\ORM\ORMException
     */
    public function entityManager(array $options): EntityManager
    {
        if ($this->entityManager) {
            return $this->entityManager;
        }

        $config = Setup::createYAMLMetadataConfiguration($options['orm']['paths'], $options['debug']);
        if (!Type::hasType('Username')) {
            Type::addType('Username', UsernameType::class);
        }
        $this->entityManager =  EntityManager::create($options['db']['connection'], $config);

        return $this->entityManager;
    }
}
