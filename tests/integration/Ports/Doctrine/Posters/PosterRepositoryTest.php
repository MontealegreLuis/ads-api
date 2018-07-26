<?php
/**
 * PHP version 7.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace Ads\Ports\Doctrine\Posters;

use Ads\ContractTests\PostersTest;
use Ads\Ports\Web\Slim\DependencyInjection\ApplicationServices;
use Ads\Posters\Posters;
use Pimple\Container;

class PosterRepositoryTest extends PostersTest
{
    protected function posters(): Posters
    {
        $container = new Container();
        $container->register(new ApplicationServices(require __DIR__ . '/../../../../../config/options.php'));
        return $container[Posters::class];
    }
}
