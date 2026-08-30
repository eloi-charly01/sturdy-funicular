<?php

namespace App\UseCase;

use App\Entity\Project;
use App\Interface\ProjectInterface;
use App\Shared\PaginationShared;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Request;

final class ProjectUseCase
{
    public function __construct(
        private readonly ProjectInterface $projectInterface,
        private readonly Security $security
    ) {}

    public function getProjects(Request $request)
    {
        $page = $request->query->getInt('page', 1);

        return PaginationShared::paginate($this->projectInterface->getProjects(), $page);
    }

    public function createOrUpdate(Project $project)
    {
        if (!$project->getOwner()) {
            $project->setOwner($this->security->getUser());
        }

        $this->projectInterface->createOrUpdate($project);
    }

    public function remove(Project $project)
    {
        $this->projectInterface->remove($project);
    }

    public function countProjects(): int
    {
        return $this->projectInterface->countProjects();
    }

    public function getTasksCountByProject(): array
    {
        return $this->projectInterface->getTasksCountByProject();
    }
}
