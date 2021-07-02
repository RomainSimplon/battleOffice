<?php

namespace App\Repository;

use App\Entity\PromoPrice;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method PromoPrice|null find($id, $lockMode = null, $lockVersion = null)
 * @method PromoPrice|null findOneBy(array $criteria, array $orderBy = null)
 * @method PromoPrice[]    findAll()
 * @method PromoPrice[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PromoPriceRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, PromoPrice::class);
    }

    // /**
    //  * @return PromoPrice[] Returns an array of PromoPrice objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('p.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?PromoPrice
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
