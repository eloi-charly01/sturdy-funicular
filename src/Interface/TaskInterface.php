<?php

namespace App\Interface;

use App\Entity\Task;
use Doctrine\ORM\QueryBuilder;

interface TaskInterface
{
    public function getAllTask(): QueryBuilder;
    public function findTask(int $id): ?Task;
    public function createOrUpdate(Task $task): void;
    public function remove(Task $task): void;
    public function countTasks(): int;
}
