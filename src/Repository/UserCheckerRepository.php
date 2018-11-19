<?php

namespace App\Repository;

use App\Entity\UserChecker;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method UserChecker|null find($id, $lockMode = null, $lockVersion = null)
 * @method UserChecker|null findOneBy(array $criteria, array $orderBy = null)
 * @method UserChecker[]    findAll()
 * @method UserChecker[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserCheckerRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, UserChecker::class);
    }

    // /**
    //  * @return UserChecker[] Returns an array of UserChecker objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('u')
            ->andWhere('u.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('u.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?UserChecker
    {
        return $this->createQueryBuilder('u')
            ->andWhere('u.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
