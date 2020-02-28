<?php

namespace App\Repository;

use App\Entity\ProductoVenta;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method ProductoVenta|null find($id, $lockMode = null, $lockVersion = null)
 * @method ProductoVenta|null findOneBy(array $criteria, array $orderBy = null)
 * @method ProductoVenta[]    findAll()
 * @method ProductoVenta[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ProductoVentaRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ProductoVenta::class);
    }

    // /**
    //  * @return ProductoVenta[] Returns an array of ProductoVenta objects
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
    public function findOneBySomeField($value): ?ProductoVenta
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
