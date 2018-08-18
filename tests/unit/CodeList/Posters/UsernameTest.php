<?php
/**
 * PHP version 7.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace Ads\CodeList\Posters;

use InvalidArgumentException;
use PHPUnit\Framework\TestCase;

class UsernameTest extends TestCase
{
    /** @test */
    function it_cannot_be_blank()
    {
        $this->expectException(InvalidArgumentException::class);
        new Username('');
    }

    /** @test */
    function it_should_be_at_least_5_characters_long()
    {
        $this->expectException(InvalidArgumentException::class);
        new Username('two');
    }

    /** @test */
    function it_accepts_alphanumeric_values_and_underscores_only()
    {
        $this->expectException(InvalidArgumentException::class);
        new Username('what??!');
    }

    /** @test */
    function it_has_more_than_5_characters_long_and_it_is_alphanumeric()
    {
        $username = new Username('thomas_anderson1');
        $this->assertEquals('thomas_anderson1', (string)$username);
    }
}
