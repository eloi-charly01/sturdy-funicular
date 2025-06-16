<?php

namespace App\Shared;

use Doctrine\ORM\QueryBuilder;
use Pagerfanta\Doctrine\ORM\QueryAdapter;
use Pagerfanta\Pagerfanta;

class PaginationShared
{
    public const PER_PAGE = 10;

    public static function paginate(QueryBuilder $query, int $page, int $maxPerPage = self::PER_PAGE): Pagerfanta
    {
        return Pagerfanta::createForCurrentPageWithMaxPerPage(
            new QueryAdapter($query),
            $page,
            $maxPerPage
        );
    }
}
