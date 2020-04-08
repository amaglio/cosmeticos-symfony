<?php

namespace App\Repository;
use App\Entity\Producto;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;
use Doctrine\ORM\EntityRepository;

class ProductoRepository extends ServiceEntityRepository
{   
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Producto::class);
    } 

    /**
     * @return Producto[]
     */
    public function findAllOrderedByName()
    {
        return $this->getEntityManager()
            ->createQuery(
                "SELECT p FROM App\Entity\Producto p where p.nombre = 'Jabon' ORDER BY p.nombre ASC"
            )
            ->getResult();
    }
}