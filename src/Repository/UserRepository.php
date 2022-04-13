<?php

namespace App\Repository;

use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class UserRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, User::class);
    }

    public function clientForUser($user): array
    {  
        $qb = $this->createQueryBuilder('u')
            ->where("u.id = :user")
            ->setParameter('user', $user);
    
        $query = $qb->getQuery();
    
        return $query->execute();
    
    }


}