<?php
/**
 * PHP version 7.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace Ads\CodeList\Authentication\Login;

use PHPUnit\Framework\TestCase;

class LoginInputTest extends TestCase
{
    /** @test */
    function it_fails_validation_if_all_values_are_empty()
    {
        $input = LoginInput::withValues([
            'username' => '  ',
            'password' => '',
        ]);

        $this->assertFalse($input->isValid());
        $this->assertCount(2, $input->errors());
        $this->assertArrayHasKey('username', $input->errors());
        $this->assertArrayHasKey('password', $input->errors());
    }

    /** @test */
    function it_fails_validation_if_all_values_are_absent()
    {
        $input = LoginInput::withValues([]);

        $this->assertFalse($input->isValid());
        $this->assertCount(2, $input->errors());
        $this->assertArrayHasKey('username', $input->errors());
        $this->assertArrayHasKey('password', $input->errors());
    }

    /** @test */
    function it_fails_validation_if_username_does_not_have_correct_format()
    {
        $input = LoginInput::withValues([
            'username' => '3lli*t!$',
            'password' => '12345678',
        ]);

        $this->assertFalse($input->isValid());
        $this->assertCount(1, $input->errors());
        $this->assertArrayHasKey('username', $input->errors());
        $this->assertArrayNotHasKey('password', $input->errors());
    }

    /** @test */
    function it_fails_validation_if_username_is_too_short()
    {
        $input = LoginInput::withValues([
            'username' => '3lli',
            'password' => '12345678',
        ]);

        $this->assertFalse($input->isValid());
        $this->assertCount(1, $input->errors());
        $this->assertArrayHasKey('username', $input->errors());
        $this->assertArrayNotHasKey('password', $input->errors());
    }
}
