<?php
/**
 * PHP version 7.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace Ads\CodeList\Ads;

use Ads\CodeList\Listings\DraftAd\DraftAdInput;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;

class AdInformationTest extends TestCase
{
    /** @test */
    function it_cannot_be_created_from_invalid_input()
    {
        $this->expectException(InvalidArgumentException::class);
        AdInformation::fromInput(DraftAdInput::withValues([]));
    }
}
