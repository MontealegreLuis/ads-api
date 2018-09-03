<?php
/**
 * PHP version 7.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace Ads\Application\Pagination\Ports;

use Ads\Application\Pagination\InvalidPage;
use Ads\Application\Pagination\Page;
use Ads\Builders\A;
use Ads\CodeList\Posters\Poster;
use Ads\CodeList\Posters\Posters;
use Ads\DependencyInjection\WithContainer;
use Doctrine\ORM\EntityManager;
use PHPUnit\Framework\TestCase;

class RepositoryPaginatorTest extends TestCase
{
    use WithContainer;

    /** @test */
    function it_cannot_get_a_page_greater_than_the_total_number_of_pages()
    {
        $paginator = RepositoryPaginator::from($this->builder);

        $this->expectException(InvalidPage::class);
        $paginator->setPage(new Page(5, 2));
    }

    /** @test */
    function it_gets_the_elements_of_the_current_page()
    {
        $posterA = A::poster()->withUsername('cdefg')->build();
        $posterB = A::poster()->withUsername('defgh')->build();
        $secondPageResults = [$posterA, $posterB];

        $this->posters->add(A::poster()->withUsername('abcde')->build());
        $this->posters->add(A::poster()->withUsername('bcdef')->build());
        $this->posters->add($posterA);
        $this->posters->add($posterB);
        $this->posters->add(A::poster()->withUsername('efghi')->build());
        $this->posters->add(A::poster()->withUsername('fghij')->build());

        $paginator = RepositoryPaginator::from($this->builder);
        $paginator->setPage(new Page(2, 2));

        $pageResults = $paginator->pageResults();

        $this->assertEquals(2, $paginator->count());
        $this->assertEquals(6, $paginator->total());
        $this->assertEquals($secondPageResults, $pageResults);
        $this->assertEquals(2, $paginator->currentPage());
        $this->assertTrue($paginator->hasPages());
        $this->assertTrue($paginator->hasNextPage());
        $this->assertEquals(3, $paginator->nextPage());
        $this->assertTrue($paginator->hasPreviousPage());
        $this->assertEquals(1, $paginator->previousPage());
        $this->assertEquals(3, $paginator->lastPage());
    }

    /** @before */
    function configure()
    {
        $this->entityManager = $this->container()->get(EntityManager::class);
        $this->entityManager
            ->createQuery('DELETE FROM ' . Poster::class)
            ->execute();
        $this->builder = $this->entityManager->createQueryBuilder()
            ->select('p')
            ->from(Poster::class, 'p')
            ->orderBy('p.username');
        $this->posters = $this->container()->get(Posters::class);
    }

    /** @var Posters */
    private $posters;

    /** @var \Doctrine\ORM\QueryBuilder */
    private $builder;

    /** @var EntityManager */
    private $entityManager;
}
