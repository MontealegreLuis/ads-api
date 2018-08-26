<?php
/**
 * PHP version 7.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace Ads\Application\StoredEvents;

use Ads\Application\Pagination\Paginator;

interface ViewEventsInPageResponder
{
    public function respondToEventsInPage(Paginator $events): void;

    public function respondToInvalidInput(array $errors): void;
}
