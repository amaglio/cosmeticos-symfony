<?php

namespace App\Controller;

use App\Entity\Venta; 
use App\Entity\Producto;
use App\Entity\ProductoVenta; 
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use App\PostTypeVenta\PostTypeVenta; 
use App\PostTypeProductoVenta\PostTypeProductoVenta; 
use Symfony\Component\HttpFoundation\Request; 
use Symfony\Component\HttpFoundation\Response;


class VentaController extends AbstractController
{
    /**
     * @Route("/ventas", name="ventas")
     */
    public function index()
    {   
        $repository = $this->getDoctrine()->getRepository(Venta::class);

        $ventas = $repository->findAll(); 
        

        return $this->render('venta/index.html.twig', [
            'controller_name' => 'VentaController',
            'ventas' => $ventas
        ]);
    }

    /**
     * @Route("/ventas/crear", name="v_crear_venta")
     */
    public function v_crear_venta(Request $request)
    {
        $venta = new Venta();
        
        $form = $this->createForm(PostTypeVenta::class, $venta);

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

            return $this->redirectToRoute('ventas');
        }
        else
        {   

            return $this->render('producto_controlller/crear.html.twig', array(
                'form' => $form->createView(),
            ));

        }
        
        
    }
    
    /**
     * @Route("/ventas/editar/{id}", name="v_editar_venta")
     */

    public function v_editar_venta( Request $request, $id )
    {   
        $venta = new Venta();
        $form = $this->createForm(PostTypeVenta::class, $venta);
        $form->handleRequest($request);

        $ProductoVenta = new ProductoVenta();
        $formProductoVenta = $this->createForm(PostTypeProductoVenta::class, $ProductoVenta, array(
            'action' => $this->generateUrl('agregar_producto_venta') 
        ));

        if ($form->isSubmitted() && $form->isValid()) 
        {   
            $entityManager = $this->getDoctrine()->getManager();
            
            $task = $form->getData();
 
            $venta = $entityManager->getRepository(Venta::class)->find($id);

            $venta->setNombre($task->getNombre());
            $venta->setFecha($task->getFecha()); 
            $venta->setTelefono($task->getTelefono());
            $venta->setEmail($task->getEmail()); 

            $entityManager->flush();

            return $this->redirectToRoute('ventas');
 
        }
        else
        {

            $repository = $this->getDoctrine()->getRepository(Venta::class);
            $venta = $repository->findOneBy(['id' => $id]);
            echo $venta->getCantidad()->count();

            //var_dump($venta->getCantidad()->getTypeClass());
            $form = $this->createForm(PostTypeVenta::class, $venta);
            
             
            return $this->render('venta/editar.html.twig', array(
                'id' => $id,
                'form' => $form->createView(),
                'form_producto_venta' => $formProductoVenta->createView()
            ));    
        }

        return new Response('<html>
            <body>
                <h1>Hello Symfony 4 World</h1>
            </body>
        </html>');
    }

    /**
     * @Route("/ventas/eliminar/{id}", name="eliminar_venta")
     */
    public function eliminar_venta( Request $request, $id )
    {   
        $entityManager = $this->getDoctrine()->getManager();
        $venta = $entityManager->getRepository(Venta::class)->find($id);
        
        if (!$venta) {
            throw $this->createNotFoundException(
                'No product found for id '.$id
            );
        }

        $entityManager->remove($venta);
        $entityManager->flush();

        return $this->redirectToRoute('ventas');
    }

    /**
     * @Route("/agergar/producto", name="agregar_producto_venta")
     */
    public function agregar_producto_venta(Request $request)
    {   
        $venta_id = $request->request->get('post_type_producto_venta')["venta_id"];
        $producto_id = $request->request->get('post_type_producto_venta')["producto_id"];
        $cantidad = $request->request->get('post_type_producto_venta')["cantidad"];

        $repository = $this->getDoctrine()->getRepository(Producto::class);
        $producto = $repository->findOneBy(['id' => $producto_id]);

        $repository = $this->getDoctrine()->getRepository(Venta::class);
        $venta = $repository->findOneBy(['id' => $venta_id]);


        $productoVenta = new ProductoVenta();

        $productoVenta->setProductoId($producto);
        $productoVenta->setVentaId($venta);
        $productoVenta->setCantidad($cantidad);
        $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($productoVenta);
            $entityManager->flush();

        
        
        //var_dump($request->query);
        
        /*
        $formProductoVenta = $this->createForm(PostTypeProductoVenta::class, $ProductoVenta, array(
            'action' => $this->generateUrl('agregar_producto_venta') 
        ));

        $formProductoVenta->handleRequest($request);*/
        
        /*
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) 
        {
 
            $task = $form->getData();
 
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($task);
            $entityManager->flush();

            return $this->redirectToRoute('ventas');
        }
        else
        {
            return $this->render('producto_controlller/crear.html.twig', array(
                'form' => $form->createView(),
            ));

        }*/
        $response = new Response('<html>
                <body>
                    <h1>Hello Symfony 4 World</h1>
                </body>
            </html>');
        return $response;
            
    }
}


