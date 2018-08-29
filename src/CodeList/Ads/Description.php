<?php
/**
 * PHP version 7.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace Ads\CodeList\Ads;

use Webmozart\Assert\Assert;

class Description
{
    /** @var string */
    private $text;

    public static function fromText(string $text): Description
    {
        return new Description($text);
    }

    private function __construct(string $text)
    {
        $this->setText(trim($text));
    }

    private function setText(string $text): void
    {
        Assert::notEmpty($text);
        Assert::lengthBetween($text, 1, 500);
        $this->text = $text;
    }

    public function text(): string
    {
        return $this->text;
    }
}
