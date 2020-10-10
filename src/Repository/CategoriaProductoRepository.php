<?php

namespace App\Repository;

use App\Entity\CategoriaProducto;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method CategoriaProducto|null find($id, $lockMode = null, $lockVersion = null)
 * @method CategoriaProducto|null findOneBy(array $criteria, array $orderBy = null)
 * @method CategoriaProducto[]    findAll()
 * @method CategoriaProducto[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CategoriaProductoRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, CategoriaProducto::class);
    }

    // /**
    //  * @return CategoriaProducto[] Returns an array of CategoriaProducto objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('c.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?CategoriaProducto
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
