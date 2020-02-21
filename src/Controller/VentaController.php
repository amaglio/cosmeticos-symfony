<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class VentaController extends AbstractController
{
    /**
     * @Route("/venta", name="venta")
     */
    public function index()
    {
        return $this->render('venta/index.html.twig', [
            'controller_name' => 'VentaController',
        ]);
    }
    
}
