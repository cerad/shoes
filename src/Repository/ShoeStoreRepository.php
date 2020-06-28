<?php

namespace App\Repository;

use App\Entity\ShoeStore;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method ShoeStore|null find($id, $lockMode = null, $lockVersion = null)
 * @method ShoeStore|null findOneBy(array $criteria, array $orderBy = null)
 * @method ShoeStore[]    findAll()
 * @method ShoeStore[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ShoeStoreRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ShoeStore::class);
    }
    public function getEntityManager() : EntityManagerInterface
    {
        return $this->_em;
    }
    public function findOneByShoeCodeStoreCode(string $shoeCode, string $storeCode): ?ShoeStore
    {
        return $this->createQueryBuilder('shoeStore')
            ->leftJoin('shoeStore.shoe','shoe')
            ->andWhere('shoeStore.store = :storeCode')
            ->andWhere('shoe.code = :shoeCode')
            ->setParameter(':storeCode', $storeCode)
            ->setParameter(':shoeCode',  $shoeCode)
            ->getQuery()
            ->getOneOrNullResult()
            ;
    }
    /**
     * @return ShoeStore[] Returns an array of ShoeStore objects
    */
    public function findAllSortedByShoe()
    {
        return $this->createQueryBuilder('shoeStore')
            ->leftJoin('shoeStore.shoe','shoe')
            ->addOrderBy('shoe.code', 'ASC')
            ->addOrderBy('shoeStore.store', 'ASC')
            //->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
}
