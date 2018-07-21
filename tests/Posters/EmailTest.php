<?php
/**
 * PHP version 7.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace Ads\Posters;

use InvalidArgumentException;
use PHPUnit\Framework\TestCase;

class EmailTest extends TestCase
{
    /** @test */
    function it_cannot_be_empty()
    {
        $this->expectException(InvalidArgumentException::class);
        Email::withAddress('');
    }

    /** @test */
    function it_cannot_have_an_invalid_format()
    {
        $this->expectException(InvalidArgumentException::class);
        Email::withAddress('notanemailaddress');
    }

    /** @test */
    function it_is_a_non_empty_valid_email_address()
    {
        $email = Email::withAddress('thomas.anderson@thematrix.org');
        $this->assertEquals('thomas.anderson@thematrix.org', $email->asText());
    }
}
