<?php
/**
 * PHP version 7.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace Ads\PHPUnit;

use Ads\Application\DataStorage\EntityManagerFactory;
use Doctrine\ORM\Tools\SchemaTool;
use PHPUnit\Framework\AssertionFailedError;
use PHPUnit\Framework\Test;
use PHPUnit\Framework\TestListener;
use PHPUnit\Framework\TestSuite;
use PHPUnit\Framework\Warning;
use Throwable;

class UpdateDatabaseSchemaListener implements TestListener
{
    public function addError(Test $test, Throwable $e, float $time): void {}

    public function addFailure(Test $test, AssertionFailedError $e, float $time): void {}

    public function addIncompleteTest(Test $test, Throwable $e, float $time): void {}

    public function addRiskyTest(Test $test, Throwable $e, float $time): void {}

    public function addSkippedTest(Test $test, Throwable $e, float $time): void {}

    /**
     * Update the database schema before running the integration tests
     */
    public function startTestSuite(TestSuite $suite): void
    {
        if ($suite->getName() !== 'integration') {
            return;
        }

        $entityManager = EntityManagerFactory::new(require __DIR__ . '/../../../config/options.php');
        $tool = new SchemaTool($entityManager);
        $tool->updateSchema($entityManager->getMetadataFactory()->getAllMetadata(), true);
    }

    public function endTestSuite(TestSuite $suite): void {}

    public function startTest(Test $test): void {}

    public function endTest(Test $test, float $time): void {}

    public function addWarning(Test $test, Warning $e, float $time): void {}
}
