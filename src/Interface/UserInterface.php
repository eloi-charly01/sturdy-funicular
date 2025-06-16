<?php

namespace App\Interface;

use Doctrine\ORM\QueryBuilder;

interface UserInterface
{
    public function getUsers(): QueryBuilder;
}
