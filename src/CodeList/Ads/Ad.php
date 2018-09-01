<?php
/**
 * PHP version 7.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace Ads\CodeList\Ads;

use Ads\CodeList\Posters\Poster;

class Ad
{
    /** @var Title */
    private $title;

    /** @var Description */
    private $description;

    /** @var int */
    private $createdAt;

    /** @var int */
    private $lastUpdatedAt;

    /** @var int */
    private $publishedOn;

    /** @var Poster */
    private $author;

    public static function draft(AdInformation $information, Poster $author): Ad
    {
        return new Ad($information->title(), $information->description(), $information->createdAt(), $author);
    }

    public function isDraft(): bool
    {
        return $this->publishedOn === null;
    }

    private function __construct(Title $title, Description $description, int $createdAt, Poster $author)
    {
        $this->title = $title;
        $this->description = $description;
        $this->createdAt = $createdAt;
        $this->author = $author;
    }
}
