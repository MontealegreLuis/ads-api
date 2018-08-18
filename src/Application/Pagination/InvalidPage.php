<?php
/**
 * PHP version 7.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace Ads\Application\Pagination;

use RuntimeException;

class InvalidPage extends RuntimeException
{
    public static function outOfRange(int $totalPages, Page $page): InvalidPage
    {
        return new InvalidPage(sprintf(
            'Cannot get page %d out of %d',
            $totalPages,
            $page->number()
        ));
    }

    public static function withNegativeNumber(int $number): InvalidPage
    {
        return new InvalidPage(sprintf('Page number must be 1 or greater, %d found', $number));
    }

    public static function withNegativeSize(int $size): InvalidPage
    {
        return new InvalidPage(sprintf('Page size must be 1 or greater, %d found', $size));
    }
}
