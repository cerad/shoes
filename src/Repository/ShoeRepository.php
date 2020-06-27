<?php

namespace App\Repository;

use App\Entity\Shoe;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Shoe|null find($id, $lockMode = null, $lockVersion = null)
 * @method Shoe|null findOneBy(array $criteria, array $orderBy = null)
 * @method Shoe[]    findAll()
 * @method Shoe[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ShoeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Shoe::class);
    }
    public function persist(Shoe $shoe) : void
    {
        $this->_em->persist($shoe);
    }
    public function flush() : void
    {
        $this->_em->flush();
    }
    public function getEntityManager() : EntityManagerInterface
    {
        return $this->_em;
    }
    // /**
    //  * @return Shoe[] Returns an array of Shoe objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('s.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    public function findOneByCode(string $code): ?Shoe
    {
        return $this->createQueryBuilder('shoe')
            ->andWhere('shoe.code = :code')
            ->setParameter(':code', $code)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
}
