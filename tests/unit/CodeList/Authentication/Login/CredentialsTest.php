<?php
/**
 * PHP version 7.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace Ads\CodeList\Authentication\Login;

use InvalidArgumentException;
use PHPUnit\Framework\TestCase;

class CredentialsTest extends TestCase
{
    /** @test */
    function it_cannot_be_created_from_invalid_input()
    {
        $this->expectException(InvalidArgumentException::class);
        Credentials::from(LoginInput::withValues([]));
    }
}
