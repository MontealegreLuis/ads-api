<?php
/**
 * PHP version 7.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace Ads\DataStorage;

use Ads\Application\DependencyInjection\ContainerFactory;
use Doctrine\ORM\EntityManager;

trait WithTableCleanup
{
    /**
     * @throws \Doctrine\DBAL\DBALException
     */
    public function empty(string ...$tables)
    {
        /** @var \Doctrine\DBAL\Connection $connection */
        $connection = ContainerFactory::new()->get(EntityManager::class)->getConnection();
        foreach ($tables as $table) {
            $platform = $connection->getDatabasePlatform();
            $connection->exec($platform->getTruncateTableSQL($table));
            if ($platform->getName() === 'sqlite') {
                $connection->exec("delete from sqlite_sequence where name='$table'");
            }
        }
    }
}
