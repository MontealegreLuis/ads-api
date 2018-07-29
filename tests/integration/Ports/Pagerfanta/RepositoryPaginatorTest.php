<?php
/**
 * PHP version 7.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace Ads\Ports\Pagerfanta;

use Ads\Builders\A;
use Ads\Ports\Pagination\InvalidPage;
use Ads\Ports\Pagination\Page;
use Ads\Ports\Web\Slim\DependencyInjection\ApplicationServices;
use Ads\Posters\Poster;
use Ads\Posters\Posters;
use Doctrine\ORM\EntityManager;
use PHPUnit\Framework\TestCase;
use Pimple\Container;

class RepositoryPaginatorTest extends TestCase
{
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

        $this->assertCount(2, $pageResults);
        $this->assertEquals($secondPageResults, $pageResults);
    }

    /** @before */
    function configure()
    {
        $container = new Container();
        $container->register(new ApplicationServices(require __DIR__ . '/../../../../config/options.php'));
        $this->entityManager = $container[EntityManager::class];
        $this->entityManager
            ->createQuery('DELETE FROM ' . Poster::class)
            ->execute();
        $this->builder = $this->entityManager->createQueryBuilder()
            ->select('p')
            ->from(Poster::class, 'p')
            ->orderBy('p.username');
        $this->posters = $container[Posters::class];
    }

    /** @var Posters */
    private $posters;

    /** @var \Doctrine\ORM\QueryBuilder */
    private $builder;

    /** @var EntityManager */
    private $entityManager;
}
