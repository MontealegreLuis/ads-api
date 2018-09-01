<?php
/**
 * PHP version 7.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace Ads\CodeList\Ads;

use Ads\CodeList\Listings\DraftAd\DraftAdInput;
use InvalidArgumentException;

class AdInformation
{
    /** @var Title */
    private $title;

    /** @var Description */
    private $description;

    /** @var int */
    private $createdAt;

    public static function fromInput(DraftAdInput $input)
    {
        if (!$input->isValid()) {
            throw new InvalidArgumentException(sprintf('Input is invalid, it has %d errors', \count($input->errors())));
        }

        return new AdInformation($input->values());
    }

    public function title(): Title
    {
        return $this->title;
    }

    public function description(): Description
    {
        return $this->description;
    }

    public function createdAt(): int
    {
        return $this->createdAt;
    }

    private function __construct(array $values)
    {
        $this->title = Title::fromText($values['title']);
        $this->description = Description::fromText($values['description']);
        $this->createdAt = $values['createdAt'];
    }
}
