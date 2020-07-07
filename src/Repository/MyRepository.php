<?php

namespace App\Repository;

use App\Entity\My;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method My|null find($id, $lockMode = null, $lockVersion = null)
 * @method My|null findOneBy(array $criteria, array $orderBy = null)
 * @method My[]    findAll()
 * @method My[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MyRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, My::class);
    }

    // /**
    //  * @return My[] Returns an array of My objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('m')
            ->andWhere('m.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('m.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?My
    {
        return $this->createQueryBuilder('m')
            ->andWhere('m.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
