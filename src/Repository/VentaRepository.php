<?php

namespace App\Repository;

use App\Entity\Venta;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method Venta|null find($id, $lockMode = null, $lockVersion = null)
 * @method Venta|null findOneBy(array $criteria, array $orderBy = null)
 * @method Venta[]    findAll()
 * @method Venta[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class VentaRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Venta::class);
    }

    /**
     * @return Venta[]
     */
    public function findByName($string)
    {   
        return $this->getEntityManager()
                    ->createQuery(
                        "SELECT v FROM App\Entity\Venta v 
                        where v.nombre LIKE  :str 
                        ORDER BY v.nombre ASC"
                    )
                    ->setParameter('str', '%'.$string.'%')
                    ->getResult();
    }
    
    /**
     * @return Venta[]
     */
    public function findByDate($fecha_inicio, $fecha_fin)
    {   
        return $this->getEntityManager()
                    ->createQuery(
                        "SELECT v FROM App\Entity\Venta v 
                        where v.fecha >=  :fecha_inicio 
                        AND  v.fecha <=  :fecha_fin
                        ORDER BY v.nombre ASC"
                    )
                    ->setParameter('fecha_inicio', '2020-04-01')
                    ->setParameter('fecha_fin', '2020-10-10')
                    ->getResult();
    }

    // /**
    //  * @return Venta[] Returns an array of Venta objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('v')
            ->andWhere('v.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('v.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Venta
    {
        return $this->createQueryBuilder('v')
            ->andWhere('v.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
