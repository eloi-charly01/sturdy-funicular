<?php

namespace App\UseCase;

use App\Interface\ProjectInterface;
use Pagerfanta\Doctrine\ORM\QueryAdapter;
use Pagerfanta\Pagerfanta;
use Symfony\Component\HttpFoundation\Request;

final class ProjectUseCase
{
    public function __construct(
        private readonly ProjectInterface $projectInterface
    ) {}

    public function getProjects(Request $request)
    {
        $page = $request->query->getInt('page', 1);

        return Pagerfanta::createForCurrentPageWithMaxPerPage(
            new QueryAdapter($this->projectInterface->getProjects()),
            $page,
            10
        );
    }
}
