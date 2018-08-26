<?php
/**
 * PHP version 7.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace Ads\Application\Pagination\Ports;

use Ads\Application\Pagination\InvalidPage;
use Ads\Application\Pagination\Page;
use Ads\Application\Pagination\Paginator;
use Doctrine\ORM\QueryBuilder;
use Pagerfanta\Adapter\DoctrineORMAdapter;
use Pagerfanta\Exception\OutOfRangeCurrentPageException;
use Pagerfanta\Pagerfanta;

/**
 * Paginator implementation using Pagerfanta and its Doctrine 2 adapter
 */
class RepositoryPaginator implements Paginator
{
    /** @var Pagerfanta  */
    private $paginator;

    public static function from(QueryBuilder $builder): RepositoryPaginator
    {
        return new RepositoryPaginator($builder);
    }

    /** @throws InvalidPage If the requested page is greater than the total number of pages */
    public function setPage(Page $page): void
    {
        $this->paginator->setMaxPerPage($page->size());
        $this->setCurrentPage($page);
    }

    public function currentPage(): int
    {
        return $this->paginator->getCurrentPage();
    }

    public function hasPages(): bool
    {
        return $this->paginator->haveToPaginate();
    }

    public function hasNextPage(): bool
    {
        return $this->paginator->hasNextPage();
    }

    public function nextPage(): int
    {
        return $this->paginator->getNextPage();
    }

    public function hasPreviousPage(): bool
    {
        return $this->paginator->hasPreviousPage();
    }

    public function previousPage(): int
    {
        return $this->paginator->getPreviousPage();
    }

    public function lastPage(): int
    {
        return $this->paginator->getNbPages();
    }

    public function pageResults(): array
    {
        return $this->paginator->getCurrentPageResults()->getArrayCopy();
    }

    /** All the elements count */
    public function total(): int
    {
        return $this->paginator->count();
    }

    /** Elements in this page count */
    public function count(): int
    {
        return \count($this->paginator->getCurrentPageResults()->getArrayCopy());
    }

    public function pageSize(): int
    {
        return $this->paginator->getMaxPerPage();
    }

    private function __construct(QueryBuilder $builder)
    {
        $this->paginator = new Pagerfanta(new DoctrineORMAdapter($builder));
    }

    /** @throws InvalidPage */
    private function setCurrentPage(Page $page): void
    {
        try {
            $this->paginator->setCurrentPage($page->number());
        } catch (OutOfRangeCurrentPageException $exception) {
            throw InvalidPage::outOfRange($this->paginator->getNbPages(), $page);
        }
    }
}
