<?php

namespace App\UseCase;

use App\Interface\UserInterface;
use App\Shared\PaginationShared;
use Symfony\Component\HttpFoundation\Request;

final class UserUseCase
{
    public function __construct(private readonly UserInterface $userInterface) {}

    public function getUsers(Request $request)
    {
        $page = $request->query->getInt('page', 1);

        return PaginationShared::paginate($this->userInterface->getUsers(), $page, 6);
    }

    public function countUsers(): int
    {
        return $this->userInterface->countUsers();
    }
}
