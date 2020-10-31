<?php

namespace App\Controller;

use App\Entity\Producto;
use App\Entity\ProductoCategoria;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class WebController extends AbstractController
{
    /** 
     * @Route("/", name="index")
     */
    public function index()
    {   $repository = $this->getDoctrine()->getRepository(Producto::class);

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
        
        $repository_categoria = $this->getDoctrine()->getRepository(ProductoCategoria::class);
        $categorias = $repository_categoria->findAll();

        return $this->render('web/shop.html.twig', [
            'controller_name' => 'shop',
            'productos' => $productos,
            'categorias' => $categorias 
        ]);

        // return $this->render('web/index.html.twig', [
        //     'controller_name' => 'WebController',
        // ]);
    }

    /**
     * @Route("/show/product/{id}", name="show_product")
     */

    public function show_product(Request $request, $id)
    {
        $entityManager = $this->getDoctrine()->getManager();
        $producto = $entityManager->getRepository(Producto::class)->find($id);

        if (!$producto) {
            throw $this->createNotFoundException(
                'There are no producto with the following id: ' . $id
            );
        }
        
        
    }
}
