<?php

namespace App\Shoe;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;

class ShoeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Shoe::class);
    }
    // This is nice but probably want to persist other shoe related entities as well
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
