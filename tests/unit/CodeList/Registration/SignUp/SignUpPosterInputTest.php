<?php
/**
 * PHP version 7.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace Ads\CodeList\Registration\SignUp;

use PHPUnit\Framework\TestCase;

class SignUpPosterInputTest extends TestCase
{
    /** @test */
    function it_fails_validation_if_all_input_are_empty()
    {
        $input = SignUpPosterInput::withValues([
            'username' => '',
            'password' => '',
            'name' => '',
            'email' => '',
        ]);

        $this->assertFalse($input->isValid());
        $this->assertArrayHasKey('username', $input->errors());
        $this->assertArrayHasKey('password', $input->errors());
        $this->assertArrayHasKey('name', $input->errors());
        $this->assertArrayHasKey('email', $input->errors());
    }

    /** @test */
    function it_fails_validation_if_no_input_is_present()
    {
        $input = SignUpPosterInput::withValues([]);

        $this->assertFalse($input->isValid());
        $this->assertArrayHasKey('username', $input->errors());
        $this->assertArrayHasKey('password', $input->errors());
        $this->assertArrayHasKey('name', $input->errors());
        $this->assertArrayHasKey('email', $input->errors());
    }

    /** @test */
    function it_fails_validation_if_password_and_username_are_shorter_than_minimum_length()
    {
        $input = SignUpPosterInput::withValues([
            'username' => '1234',
            'password' => '1234567',
            'name' => 'Thomas Anderson',
            'email' => 'thomas.anderson@thematrix.org',
        ]);

        $this->assertFalse($input->isValid());

        $this->assertArrayHasKey('username', $input->errors());
        $this->assertArrayHasKey('password', $input->errors());

        $this->assertArrayNotHasKey('name', $input->errors());
        $this->assertArrayNotHasKey('email', $input->errors());
    }

    /** @test */
    function it_fails_validation_if_username_includes_non_alphanumeric_characters_or_underscores()
    {
        $input = SignUpPosterInput::withValues([
            'username' => '3lli*t!$',
            'password' => '12345678',
            'name' => 'Thomas Anderson',
            'email' => 'thomas.anderson@thematrix.org',
        ]);

        $this->assertFalse($input->isValid());

        $this->assertArrayHasKey('username', $input->errors());

        $this->assertArrayNotHasKey('password', $input->errors());
        $this->assertArrayNotHasKey('name', $input->errors());
        $this->assertArrayNotHasKey('email', $input->errors());
    }

    /** @test */
    function it_fails_validation_if_no_valid_email_is_provided()
    {
        $input = SignUpPosterInput::withValues([
            'username' => 'elliot_alderson',
            'password' => '12345678',
            'name' => 'Thomas Anderson',
            'email' => 'not_an_email!',
        ]);

        $this->assertFalse($input->isValid());

        $this->assertArrayHasKey('email', $input->errors());

        $this->assertArrayNotHasKey('password', $input->errors());
        $this->assertArrayNotHasKey('username', $input->errors());
        $this->assertArrayNotHasKey('name', $input->errors());
    }

    /** @test */
    function it_passes_validation_with_correct_input()
    {
        $input = SignUpPosterInput::withValues([
            'username' => 'elliot_alderson',
            'password' => '12345678',
            'name' => 'Thomas Anderson',
            'email' => 'thomas.anderson@thematrix.org',
        ]);

        $this->assertTrue($input->isValid());
        $this->assertEmpty($input->errors());
    }
}
