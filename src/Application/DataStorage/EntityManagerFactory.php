<?php
/**
 * PHP version 7.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace Ads\Application\DataStorage;

use Ads\Application\DataStorage\Doctrine\Types\UsernameType;
use Doctrine\DBAL\Logging\DebugStack;
use Doctrine\DBAL\Types\Type;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Tools\Setup;

class EntityManagerFactory
{
    /** @var EntityManager */
    private static $entityManager;

    /**
     * Singleton instance
     *
     * @throws \Doctrine\DBAL\DBALException
     * @throws \Doctrine\ORM\ORMException
     */
    public static function new(array $options): EntityManager
    {
        if (self::$entityManager) {
            return self::$entityManager;
        }

        self::$entityManager = self::create($options);

        return self::$entityManager;
    }

    /**
     * @throws \Doctrine\DBAL\DBALException
     * @throws \Doctrine\ORM\ORMException
     */
    public static function create(array $options): EntityManager
    {
        $config = Setup::createYAMLMetadataConfiguration($options['mappingPaths'], $options['debug']);
        $config->setSQLLogger(new DebugStack());
        if (!Type::hasType('Username')) {
            Type::addType('Username', UsernameType::class);
        }
        return EntityManager::create($options['connection'], $config);
    }
}
