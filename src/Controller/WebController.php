<?php

namespace App\Controller;

use App\Entity\Producto;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class WebController extends AbstractController
{
    /** 
     * @Route("/", name="index")
     */
    public function index()
    {   
        $repository = $this->getDoctrine()->getRepository(Producto::class);

        $productos_destacados = $repository->findBy(
            ['destacado_home' => 1]
        );
 
        return $this->render('web/index.html.twig', [
            'controller_name' => 'Index',
            'productos_destacados' => $productos_destacados
        ]);

        // return $this->render('web/index.html.twig', [
        //     'controller_name' => 'WebController',
        // ]);
    }

    /** 
     * @Route("/shop", name="shop")
     */
    public function shop()
    {   
        $repository = $this->getDoctrine()->getRepository(Producto::class);

        $productos = $repository->findBy(
            ['enabled' => 1]
        );
 
        return $this->render('web/shop.html.twig', [
            'controller_name' => 'shop',
            'productos' => $productos 
        ]);

        // return $this->render('web/index.html.twig', [
        //     'controller_name' => 'WebController',
        // ]);
    }
}
