<?php
/**
 * PHP version 7.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace Ads\CodeList\Listings\DraftAd;

use Ads\Application\Validation\InputValidator;
use Carbon\Carbon;
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

    /** @var int */
    private $createdAt;

    public static function withValues(array $values): DraftAdInput
    {
        return new DraftAdInput($values);
    }

    public function values(): array
    {
        return [
            'title' => $this->title,
            'description' => $this->description,
            'createdAt' => $this->createdAt
        ];
    }

    public function __construct(array $values)
    {
        parent::__construct();
        $this->title = trim($values['title'] ?? '');
        $this->description = trim($values['description'] ?? '');
        $this->createdAt = $values['createdAt'] ?? Carbon::now('UTC')->getTimestamp();;
    }
}
