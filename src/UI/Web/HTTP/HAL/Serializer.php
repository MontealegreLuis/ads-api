<?php
/**
 * PHP version 7.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace Ads\UI\Web\HTTP\HAL;

use Ads\Application\Pagination\Paginator;
use Ads\UI\Web\HTTP\HAL\Mappings\ObjectMapping;
use Ads\UI\Web\HTTP\HAL\Mappings\PosterMapping;
use Ads\UI\Web\HTTP\HAL\Mappings\StoredEventMapping;
use Ads\UI\Web\HTTP\HAL\Mappings\UriBuilder;
use NilPortugues\Api\Hal\HalPagination;
use NilPortugues\Api\Hal\HalSerializer;
use NilPortugues\Api\Hal\JsonTransformer;
use NilPortugues\Api\Mapping\Mapper;

class Serializer
{
    /** @var HalSerializer */
    private $halSerializer;

    /** @var UriBuilder */
    private $uriBuilder;

    public static function hal(UriBuilder $uriBuilder): Serializer
    {
        return new Serializer($uriBuilder);
    }

    public function serializeItem($item): string
    {
        return $this->halSerializer->serialize($item);
    }

    public function serializePaginatedCollection(Paginator $paginator, string $routeName): string
    {
        $page = new HalPagination();
        $page->setTotal($paginator->total());
        $page->setCount($paginator->count());
        $page->setSelf($this->uriBuilder->pathFor($routeName, [], ['page' => $paginator->currentPage()]));
        $page->setFirst($this->uriBuilder->pathFor($routeName, [], ['page' => 1]));
        if ($paginator->hasNextPage()) {
            $page->setNext($this->uriBuilder->pathFor($routeName, [], ['page' => $paginator->nextPage()]));
        }
        if ($paginator->hasPreviousPage()) {
            $page->setPrev($this->uriBuilder->pathFor($routeName, [], ['page' => $paginator->previousPage()]));
        }
        $page->setLast($this->uriBuilder->pathFor($routeName, [], ['page' => $paginator->lastPage()]));
        $page->setEmbedded($paginator->pageResults());

        return $this->halSerializer->serialize($page);
    }

    private function __construct(UriBuilder $uriBuilder)
    {
        $mappings = [
            ObjectMapping::fromMapper(new PosterMapping($uriBuilder)),
            ObjectMapping::fromMapper(new StoredEventMapping()),
        ];
        $this->halSerializer = new HalSerializer(new JsonTransformer(new Mapper($mappings)));
        $this->uriBuilder = $uriBuilder;
    }
}
