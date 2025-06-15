<?php

namespace App\Interface;

use App\Entity\Project;
use Doctrine\ORM\QueryBuilder;

interface ProjectInterface
{
    public function getProjects(): QueryBuilder;
    public function createOrUpdate(Project $project): void;
    public function remove(Project $project): void;
}
