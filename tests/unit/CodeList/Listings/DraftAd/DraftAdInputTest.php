<?php
/**
 * PHP version 7.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace Ads\CodeList\Listings\DraftAd;

use PHPUnit\Framework\TestCase;

class DraftAdInputTest extends TestCase
{
    /** @test */
    function it_does_not_pass_validation_if_values_are_empty()
    {
        $input = DraftAdInput::withValues(['title' => '  ', 'description' => '    ']);

        $this->assertFalse($input->isValid());
        $this->assertCount(2, $input->errors());
        $this->assertArrayHasKey('title', $input->errors());
        $this->assertArrayHasKey('description', $input->errors());
    }

    /** @test */
    function it_does_not_pass_validation_if_values_are_absent()
    {
        $input = DraftAdInput::withValues([]);

        $this->assertFalse($input->isValid());
        $this->assertCount(2, $input->errors());
        $this->assertArrayHasKey('title', $input->errors());
        $this->assertArrayHasKey('description', $input->errors());
    }

    /** @test */
    function it_does_not_pass_validation_if_values_are_too_long()
    {
        $input = DraftAdInput::withValues([
            'title' => str_repeat('a', 51),
            'description' => str_repeat('a', 501),
        ]);

        $this->assertFalse($input->isValid());
        $this->assertCount(2, $input->errors());
        $this->assertArrayHasKey('title', $input->errors());
        $this->assertArrayHasKey('description', $input->errors());
    }

    /** @test */
    function it_passes_validation_with_correct_input()
    {
        $input = DraftAdInput::withValues([
            'title' => 'Valid title',
            'description' => 'Valid description',
        ]);

        $this->assertTrue($input->isValid());
        $this->assertEmpty($input->errors());
    }
}
