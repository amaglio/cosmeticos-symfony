<?php

namespace App\Controller;

use App\Entity\Producto; 
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route; 
use App\FormCrear\PostType; 
use Symfony\Component\HttpFoundation\Request;  

class ProductoControlllerController extends AbstractController
{
    /**
     * @Route("/productos", name="producto_controlller")
     * Productos habilitados
     */
    public function index()
    {   
        $repository = $this->getDoctrine()->getRepository(Producto::class);

        $productos = $repository->findBy(
            ['enabled' => 1 ] 
        ); 
 
        return $this->render('producto_controlller/index.html.twig', [
            'controller_name' => 'Productos',
            'productos' => $productos
        ]); 
    }   


    /**
     * @Route("/productos/crear", name="v_crear_producto")
     */
    public function v_crear_producto(Request $request)
    {
         $producto = new Producto();
        
        $form = $this->createForm(PostType::class, $producto);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) 
        {
            // $form->getData() holds the submitted values
            // but, the original `$task` variable has also been updated
            $task = $form->getData();

            // ... perform some action, such as saving the task to the database
            // for example, if Task is a Doctrine entity, save it!
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($task);
            $entityManager->flush();

            return $this->redirectToRoute('producto_controlller');
        }
        else
        {
            return $this->render('producto_controlller/crear.html.twig', array(
                'form' => $form->createView(),
            ));

        }
        
        
    }
    


   
    /**
     * @Route("/productos/traer", name="json_producto")
     * Productos JSON
     */
    public function find_productos()
    {   
        $repository = $this->getDoctrine()->getRepository(Producto::class);

        $productos = $repository->findBy(
            ['enabled' => 1 ] 
        ); 

        return $this->json($productos);
      
    }   


}   
