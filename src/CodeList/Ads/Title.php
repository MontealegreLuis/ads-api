<?php
/**
 * PHP version 7.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace Ads\CodeList\Ads;

use Webmozart\Assert\Assert;

class Title
{
    /** @var string */
    private $text;

    public static function fromText(string $text): Title
    {
        return new Title($text);
    }

    private function __construct(string $text)
    {
        $this->setText(trim($text));
    }

    private function setText(string $text): void
    {
        Assert::notEmpty($text);
        Assert::lengthBetween($text, 1, 50);
        $this->text = $text;
    }

    public function text(): string
    {
        return $this->text;
    }
}
