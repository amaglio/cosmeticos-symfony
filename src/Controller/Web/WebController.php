<?php

namespace App\Controller\Web;

use App\Entity\Producto;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class WebController extends AbstractController
{
    /**
     * @Route("/web", name="web")
     */
    public function index()
    {
        return $this->render('web/index.html.twig', [
            'controller_name' => 'WebController',
        ]);
    }

    /**
     * @Route("/web/listado", name="web_listado")
     * Productos habilitados
     */
    public function productos_pdf_nuevo()
    {   
        $repository = $this->getDoctrine()->getRepository(Producto::class);

        $productos = $repository->findBy(
            ['enabled' => 1,'catalogo'=> 1  ]
        );
 
        return $this->render('producto_controlller/pdf_view.html.twig', [
            'controller_name' => 'Productos',
            'productos' => $productos
        ]);
    }
}
