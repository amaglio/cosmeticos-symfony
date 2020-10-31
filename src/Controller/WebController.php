<?php

namespace App\Controller;

use App\Entity\Producto;
use App\Entity\ProductoCategoria;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;

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

        $normalizer = new ObjectNormalizer();
        $encoder = new JsonEncoder();
        
        $serializer = new Serializer([$normalizer], [$encoder]);
        //$serializer->serialize($producto, 'json', ['ignored_attributes' => ['categoria']]); 

        $response = new Response();
        $response->setContent( $serializer->serialize($producto, 'json', ['ignored_attributes' => ['categoria']]));
        $response->headers->set('Content-Type', 'application/json');
        return $response; 
    }

    /**
     * @Route("/get/product/type/{id}", name="get_product_by_type")
     */

    public function get_product_by_type(Request $request, $id)
    {    
        // $entityManager = $this->getDoctrine()->getManager();
        // $productos = $entityManager->getRepository(Producto::class)->findByCategory($id);
        $repository = $this->getDoctrine()->getRepository(Producto::class);

        $productos = $repository->findBy(
            ['enabled' => 1, 'categoria' => $id]
        );

        if (!$productos) {
            throw $this->createNotFoundException(
                'There are no producto with the categori id: ' . $id
            );
        }

        $normalizer = new ObjectNormalizer();
        $encoder = new JsonEncoder();
        
        $serializer = new Serializer([$normalizer], [$encoder]);
        //$serializer->serialize($productos, 'json', ['ignored_attributes' => ['categoria']]); 

        $response = new Response();
        $response->setContent( $serializer->serialize($productos,'json',['ignored_attributes' => ['categoria']]));
        $response->headers->set('Content-Type', 'application/json');
        return $response; 
    }
}
