<?php
/**
 * PHP version 7.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace Ads\CodeList\Listings\DraftAd;

use Ads\Application\Validation\InputValidator;
use Ads\CodeList\Ads\Description;
use Ads\CodeList\Ads\Title;
use Ads\CodeList\Clock;
use Symfony\Component\Validator\Constraints as Assert;

class DraftAdInput extends InputValidator
{
    /**
     * @Assert\NotBlank()
     * @Assert\Length(min = 1, max = 50)
     */
    private $title;

    /**
     * @Assert\NotBlank()
     * @Assert\Length(min = 1, max = 500)
     */
    private $description;

    /** @var string */
    private $author;

    /** @var int */
    private $createdAt;

    public static function withValues(array $values): DraftAdInput
    {
        return new DraftAdInput($values);
    }

    public function title(): Title
    {
        return Title::fromText($this->title);
    }

    public function description(): Description
    {
        return Description::fromText($this->description);
    }

    public function createdAt(): int
    {
        return $this->createdAt;
    }

    public function author(): string
    {
        return $this->author;
    }

    public function __construct(array $values)
    {
        parent::__construct();
        $this->title = trim($values['title'] ?? '');
        $this->description = trim($values['description'] ?? '');
        $this->author = trim($values['author'] ?? '');
        $this->createdAt = $values['createdAt'] ?? Clock::currentTimestamp();
    }
}
