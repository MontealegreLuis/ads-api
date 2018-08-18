<?php
/**
 * PHP version 7.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace Ads\DependencyInjection;

use Ads\Application\DependencyInjection\ApplicationServices;
use Pimple\Container;

trait WithContainer
{
    /** @var Container */
    private $container;

    public function container(): Container
    {
        if (!$this->container) {
            $this->container = new Container();
            $this->container->register(new ApplicationServices(require __DIR__ . '/../../../config/options.php'));
        }

        return $this->container;
    }
}
