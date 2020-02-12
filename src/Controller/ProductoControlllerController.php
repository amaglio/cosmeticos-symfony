<?php

namespace App\Controller;

use App\Entity\Producto;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;

class ProductoControlllerController extends AbstractController
{
    /**
     * @Route("/producto/controlller", name="producto_controlller")
     */
    public function index()
    {
        return $this->render('producto_controlller/index.html.twig', [
            'controller_name' => 'ProductoControlllerController',
        ]);
    }

    /**
     * @Route("/producto", name="create_product")
     */
    public function createProduct(): Response
    {
        // you can fetch the EntityManager via $this->getDoctrine()
        // or you can add an argument to the action: createProduct(EntityManagerInterface $entityManager)
        $entityManager = $this->getDoctrine()->getManager();

        $producto = new Producto();
        $producto->setNombre('Keyboard');
        $producto->setPrecioCosto(150);
        $producto->setStock(10);

        // tell Doctrine you want to (eventually) save the producto (no queries yet)
        $entityManager->persist($producto);

        // actually executes the queries (i.e. the INSERT query)
        $entityManager->flush();

        return new Response('Saved new product with id '.$producto->getId());
    }
}
