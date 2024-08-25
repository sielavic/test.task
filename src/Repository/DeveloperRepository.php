<?php

namespace App\Repository;

use App\Entity\Developer;
use App\Entity\Project;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;



class DeveloperRepository extends ServiceEntityRepository{
    public function __construct(ManagerRegistry $registry) {
        parent::__construct($registry, Developer::class);
    }

}