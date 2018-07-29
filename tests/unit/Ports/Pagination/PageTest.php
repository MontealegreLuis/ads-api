<?php
/**
 * PHP version 7.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace Ads\Ports\Pagination;

use PHPUnit\Framework\TestCase;

class PageTest extends TestCase
{
    /** @test */
    function it_must_have_a_size_greater_than_zero()
    {
        $this->expectException(InvalidPage::class);
        new Page(0, 1);
    }

    /** @test */
    function it_must_have_a_number_greater_than_zero()
    {
        $this->expectException(InvalidPage::class);
        new Page(10, 0);
    }
}
