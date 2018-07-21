<?php
/**
 * PHP version 7.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace Ads\Posters;

use Webmozart\Assert\Assert;

/**
 * A poster's name
 *
 * @see Poster
 * @see NameTest
 */
class Name
{
    /** @var string */
    private $name;

    public function __construct(string $name)
    {
        $this->setName($name);
    }

    public function asText(): string
    {
        return $this->name;
    }

    private function setName(string $name): void
    {
        Assert::notEmpty($name, 'A name cannot be empty');
        $this->name = $name;
    }
}
