<?php

namespace App\UseCase;

use App\Entity\Task;
use App\Interface\TaskInterface;
use App\Shared\PaginationShared;
use Symfony\Component\HttpFoundation\Request;

final class TaskUseCase
{
    public function __construct(
        readonly  private  TaskInterface $taskInterface,
    ) {}

    public function getAllTask(Request $request)
    {
        $page = $request->query->getInt('page', 1);
        return PaginationShared::paginate($this->taskInterface->getAllTask(), $page);
    }

    public function createOrUpdate(Task $task)
    {
        return $this->taskInterface->createOrUpdate($task);
    }

    public function remove(Task $task)
    {
        return $this->taskInterface->remove($task);
    }
}
