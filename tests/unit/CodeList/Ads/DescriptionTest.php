<?php
/**
 * PHP version 7.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace Ads\CodeList\Ads;

use InvalidArgumentException;
use PHPUnit\Framework\TestCase;

class DescriptionTest extends TestCase
{
    /** @test */
    function it_cannot_be_empty()
    {
        $this->expectException(InvalidArgumentException::class);
        Description::fromText('   ');
    }

    /** @test */
    function it_cannot_be_longer_than_50_characters()
    {
        $this->expectException(InvalidArgumentException::class);
        Description::fromText(str_repeat('a', 501));
    }

    /** @test */
    function it_gets_its_text_value()
    {
        $description = Description::fromText('This is a test description');

        $this->assertEquals('This is a test description', $description->text());
    }
}
