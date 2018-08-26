<?php
/**
 * PHP version 7.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace Ads\Application\StoredEvents;

use Ads\Application\Pagination\Page;
use Ads\Application\Validation\InputValidator;
use Symfony\Component\Validator\Constraints as Assert;

class ViewEventsInPageInput extends InputValidator
{
    private const PAGE_SIZE = 5;

    /**
     * @Assert\GreaterThanOrEqual(1)
     */
    private $page;

    public static function withValues(array $values): ViewEventsInPageInput
    {
        return new ViewEventsInPageInput($values);
    }

    public function page(): Page
    {
        return new Page(self::PAGE_SIZE, $this->page);
    }

    protected function __construct(array $values)
    {
        parent::__construct();
        $this->page = $values['page'] ?? 1;
    }
}
