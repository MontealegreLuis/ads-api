<?php
/**
 * PHP version 7.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace Ads\CodeList\Ads;

use InvalidArgumentException;
use PHPUnit\Framework\TestCase;

class TitleTest extends TestCase
{
    /** @test */
    function it_cannot_be_empty()
    {
        $this->expectException(InvalidArgumentException::class);
        Title::fromText('   ');
    }

    /** @test */
    function it_cannot_be_longer_than_50_characters()
    {
        $this->expectException(InvalidArgumentException::class);
        Title::fromText('123456789012345678901234567890123456789012345678901');
    }

    /** @test */
    function it_gets_its_text_value()
    {
        $title = Title::fromText('This is a test title');

        $this->assertEquals('This is a test title', $title->text());
    }
}
