<?php

namespace App\Interface;

use Doctrine\ORM\QueryBuilder;

interface ProjectInterface
{
    public function getProjects(): QueryBuilder;
}
