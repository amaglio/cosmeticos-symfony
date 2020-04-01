<?php

namespace App\Repository;

use App\Entity\Producto;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;
use Doctrine\ORM\EntityRepository;

/**
 * @method Producto|null find($id, $lockMode = null, $lockVersion = null)
 * @method Producto|null findOneBy(array $criteria, array $orderBy = null)
 * @method Producto[]    findAll()
 * @method Producto[]    findAllEnabled()
 * @method Producto[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ProductoRepository extends ServiceEntityRepository
{   
     
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Producto::class);
    } 

    /**
     * @return Producto[]
     */

    public function findAllEnabled(): array
    {
        $entityManager = $this->getEntityManager();

        $query = $entityManager->createQuery(
            'SELECT p
             FROM App\Entity\Producto p
             WHERE p.enabled IS NOT NULL
             ORDER BY p.nombre ASC'
        );

        // returns an array of Product objects
        return $query->getResult();
    }
 
    // MOVIES 
     public function transform(Producto $producto)
    {
        return [
                'id'    => (int) $producto->getId(),
                'nombre' => (string) $producto->getNombre(),
                'precioCosto' => (float) $producto->getPrecioCosto()
        ];
    }

    public function getProductosEnabled()
    {
        $productos = $this->findBy(
            ['enabled' => 1 ] 
        ); 

        $productosArray = [];

        foreach ($productos as $producto) {
            $productosArray[] = $this->transform($producto);
        }

        return $productosArray;
    } 
    
}
