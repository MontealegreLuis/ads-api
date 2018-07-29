<?php
/**
 * PHP version 7.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace Ads\Ports\Pagination;

class Page
{
    /** @var int */
    private $size;

    /** @var int */
    private $number;

    /** @throws InvalidPage If a negative page number is provided */
    public function __construct(int $size, int $number)
    {
        $this->setSize($size);
        $this->setPageNumber($number);
    }

    public function size(): int
    {
        return $this->size;
    }

    public function number(): int
    {
        return $this->number;
    }

    /** @throws InvalidPage */
    private function setPageNumber(int $number): void
    {
        if ($number < 1) {
            throw InvalidPage::withNegativeNumber($number);
        }
        $this->number = $number;
    }

    /**
     * @param int $size
     */
    private function setSize(int $size): void
    {
        if ($size < 1) {
            throw InvalidPage::withNegativeSize($size);
        }
        $this->size = $size;
    }
}
