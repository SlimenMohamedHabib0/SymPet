<?php

namespace App\Service;

use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;

class PaginationService
{
    public function __construct(private PaginatorInterface $paginator) {}

    public function paginate(mixed $query, Request $request, int $limit = 8): mixed
    {
        return $this->paginator->paginate(
            $query,
            $request->query->getInt('page', 1),
            $limit
        );
    }
}
