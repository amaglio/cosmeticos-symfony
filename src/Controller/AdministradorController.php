<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;

class AdministradorController extends AbstractController
{
    /**
     * @Route("/administrador", name="administrador")
     */
    public function index2()
    {
        return $this->render('administrador/index.html.twig', [
            'controller_name' => 'AdministradorController',
        ]);
    }

    /**
     * @Route("/response2", name="response")
     */
    public function response()
    {
        $response = new Response('<html>
                <body>
                    <h1>Hello Symfony 4 World</h1>
                </body>
            </html>');
        return $response;
    }

    /**
     * @Route("/pagina2/{nombre}", name="pagina2")
     */
    public function pagina2($nombre)
    {
        return $this->render('administrador/pagina2.html.twig', [
            'nombre' => $nombre,
        ]  );
    }

     /**
     * @Route("/pagina3", name="pagina3")
     */
    public function pagina3()
    {
        return $this->render('administrador/pagina3.html.twig' );
    }

}
