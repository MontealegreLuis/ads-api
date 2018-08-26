<?php
/**
 * PHP version 7.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace Ads\Application\StoredEvents;

use Ads\Application\DomainEvents\EventStore;
use LogicException;

class ViewEventsInPageAction
{
    /** @var EventStore */
    private $store;

    /** @var ViewEventsInPageResponder */
    private $responder;

    public function __construct(EventStore $store)
    {
        $this->store = $store;
    }

    public function viewPage(ViewEventsInPageInput $input): void
    {
        if ($input->isValid()) {
            $events = $this->store->eventsIn($input->page());
            $this->responder()->respondToEventsInPage($events);
        } else {
            $this->responder()->respondToInvalidInput($input->errors());
        }
    }

    public function attach(ViewEventsInPageResponder $responder): void
    {
        $this->responder = $responder;
    }

    /** @throws LogicException if no responder has been attached */
    private function responder(): ViewEventsInPageResponder
    {
        if (!$this->responder) {
            throw new LogicException('Cannot view page of events without a responder');
        }
        return $this->responder;
    }
}
