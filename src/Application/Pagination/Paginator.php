<?php
/**
 * PHP version 7.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace Ads\Application\Pagination;

interface Paginator
{
    /** @throws InvalidPage */
    public function setPage(Page $page): void;

    public function currentPage(): int;

    public function hasPages(): bool;

    public function hasNextPage(): bool;

    public function nextPage(): int;

    public function hasPreviousPage(): bool;

    public function previousPage(): int;

    public function lastPage(): int;

    public function pageResults(): array;

    /** All the elements count */
    public function total(): int;

    /** Elements in this page count */
    public function count(): int;

    public function pageSize(): int;
}
