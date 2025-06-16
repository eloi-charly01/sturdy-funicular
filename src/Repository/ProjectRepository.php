<?php

namespace App\Repository;

use App\Entity\Project;
use App\Interface\ProjectInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Project>
 */
class ProjectRepository extends ServiceEntityRepository implements ProjectInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Project::class);
    }

    public function getProjects(): QueryBuilder
    {
        return $this->createQueryBuilder('p');
    }

    public function createOrUpdate(Project $project): void
    {
        if (!$project->getId()) {
            $this->getEntityManager()->persist($project);
        }
        $this->getEntityManager()->flush();
    }

    public function remove(Project $project): void
    {
        $this->getEntityManager()->remove($project);
        $this->getEntityManager()->flush();
    }


    public function countProjects(): int
    {
        return $this->createQueryBuilder('p')
            ->select('COUNT(p.id)')
            ->getQuery()
            ->getSingleScalarResult();
    }
}
