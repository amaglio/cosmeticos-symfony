<?php

namespace App\Controller;

use App\Entity\Venta; 
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class VentaController extends AbstractController
{
    /**
     * @Route("/ventas", name="ventas")
     */
    public function index()
    {   
        $repository = $this->getDoctrine()->getRepository(Venta::class);

        $productos = $repository->findBy(
            ['enabled' => 1 ] 
        ); 

        return $this->render('venta/index.html.twig', [
            'controller_name' => 'VentaController',
        ]);
    }
    
}
