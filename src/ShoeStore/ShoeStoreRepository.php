<?php

namespace App\ShoeStore;

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
            ->addSelect('shoe')
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
    public function findAllForStoreCodes(array $storeCodes = [])
    {
        $qb = $this->createQueryBuilder('shoeStore');
        $qb->addSelect('shoe');
        $qb->leftJoin('shoeStore.shoe','shoe');
        if (count($storeCodes)) {
            $qb->andWhere('shoeStore.store IN (:storeCodes)');
            $qb->setParameter('storeCodes',$storeCodes);
        }
        $qb->addOrderBy('shoe.code', 'ASC');
        $qb->addOrderBy('shoeStore.store', 'ASC');

        return $qb->getQuery()->getResult();
    }
    /**
     * @return ShoeStore[] Returns an array of ShoeStore objects
     */
    public function findAllSortedByStore()
    {
        return $this->createQueryBuilder('shoeStore')
            ->addSelect('shoe')
            ->leftJoin('shoeStore.shoe','shoe')
            ->addOrderBy('shoeStore.store', 'ASC')
            ->addOrderBy('shoe.code', 'ASC')
            ->getQuery()
            ->getResult()
            ;
    }

    /**
     * @return ShoeStore[] Returns an array of ShoeStore objects
     */
    public function findAllForStore(string $storeCode)
    {
        return $this->createQueryBuilder('shoeStore')
            ->addSelect('shoe')
            ->leftJoin('shoeStore.shoe','shoe')
            ->andWhere('shoeStore.store = :storeCode')
            ->setParameter('storeCode', $storeCode)
            ->addOrderBy('shoe.code', 'ASC')
            ->getQuery()
            ->getResult()
            ;
    }
    public function findAllStoreCodes() : array
    {
        $conn = $this->getEntityManager()->getConnection();
        $stmt = $conn->prepare('SELECT DISTINCT store FROM shoe_stores ORDER BY store');
        $stmt->execute();
        $rows = $stmt->fetchAll();
        $storeCodes = [];
        foreach ($rows as $row) {
            $storeCode = $row['store'];
            $storeCodes[$storeCode] = $storeCode;
        }
        return $storeCodes;
    }
}
