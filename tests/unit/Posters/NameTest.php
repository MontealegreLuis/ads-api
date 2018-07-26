<?php
/**
 * PHP version 7.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace Ads\Posters;

use InvalidArgumentException;
use PHPUnit\Framework\TestCase;

class NameTest extends TestCase
{
    /** @test */
    function it_cannot_be_empty()
    {
        $this->expectException(InvalidArgumentException::class);
        new Name('');
    }

    /** @test */
    function it_is_a_free_format_non_empty_string()
    {
        $name = new Name('Thomas Anderson');

        $this->assertEquals('Thomas Anderson', $name->asText());
    }
}
