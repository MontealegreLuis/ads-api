<?php
/**
 * PHP version 7.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace Ads\CodeList\Posters;

use InvalidArgumentException;
use PHPUnit\Framework\TestCase;

class PasswordTest extends TestCase
{
    /** @test */
    function it_cannot_be_empty()
    {
        $this->expectException(InvalidArgumentException::class);
        Password::fromPlainText('');
    }

    /** @test */
    function it_must_be_at_least_8_characters_long()
    {
        $this->expectException(InvalidArgumentException::class);
        Password::fromPlainText('short');
    }

    /** @test */
    function it_can_be_verified_after_it_has_been_hashed()
    {
        $password = Password::fromPlainText('ilovemyjob');

        $this->assertTrue($password->verify('ilovemyjob'));
        $this->assertFalse($password->verify('somethingelse'));
    }
}
