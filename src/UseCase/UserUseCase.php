<?php

namespace App\UseCase;

use App\Interface\UserInterface;
use Pagerfanta\Doctrine\ORM\QueryAdapter;
use Pagerfanta\Pagerfanta;
use Symfony\Component\HttpFoundation\Request;

final class UserUseCase
{
    public function __construct(private readonly UserInterface $userInterface) {}

    public function getUsers(Request $request)
    {
        $page = $request->query->getInt('page', 1);

        return Pagerfanta::createForCurrentPageWithMaxPerPage(
            new QueryAdapter($this->userInterface->getUsers()),
            $page,
            6
        );
    }

    public function countUsers(): int
    {
        return $this->userInterface->countUsers();
    }
}
