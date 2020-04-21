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

            return $this->render('venta/crear.html.twig', array(
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
    
        if ($form->isSubmitted() && $form->isValid()) 
        {   
            $entityManager = $this->getDoctrine()->getManager();      
            $form_data = $form->getData();
 
            $venta = $entityManager->getRepository(Venta::class)->find($id);

            $venta->setNombre($form_data->getNombre());
            $venta->setFecha($form_data->getFecha()); 
            $venta->setTelefono($form_data->getTelefono());
            $venta->setEmail($form_data->getEmail()); 

            $entityManager->flush();

            return $this->redirectToRoute('ventas');
        }
        else

        {   
            
            // Obtengo la venta
            $repository = $this->getDoctrine()->getRepository(Venta::class);
            $venta = $repository->findOneBy(['id' => $id]);
            $productosVenta = $venta->getProductosVenta()->toArray() ;

            // Creo formulario de agregar productos a la venta
            $ProductoVenta = new ProductoVenta();
            $formProductoVenta = $this->createForm(     PostTypeProductoVenta::class, 
                                                        $ProductoVenta, 
                                                        array(
                                                            'action' => $this->generateUrl('agregar_producto_venta') 
                                                        )
                                                    );
                                                    
            $formProductoVenta->get('venta_id')->setData($id);

            // Obtengo el formulario de venta para editar
            $form = $this->createForm(PostTypeVenta::class, $venta);

            // Obtengo los productos
            $repository = $this->getDoctrine()->getRepository(Producto::class);
            $listadoProductos = $repository->findAll(); 
             
            
            return $this->render('venta/editar.html.twig', array(
                'id' => $id,
                'form' => $form->createView(),
                'productos' => $productosVenta,
                'listadoProductos' => $listadoProductos,
                'form_producto_venta' => $formProductoVenta->createView()
            ));     
        }

        // return new Response('<html>
        //     <body>
        //         <h1>Hello Symfony 4 World</h1>
        //     </body>
        // </html>');
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
        $precio_costo = $request->request->get('post_type_producto_venta')["precio_costo"];
        $precio_venta = $request->request->get('post_type_producto_venta')["precio_venta"];

        $repository = $this->getDoctrine()->getRepository(Producto::class);
        $producto = $repository->findOneBy(['id' => $producto_id]);

        $repository = $this->getDoctrine()->getRepository(Venta::class);
        $venta = $repository->findOneBy(['id' => $venta_id]);


        $productoVenta = new ProductoVenta();

        $productoVenta->setProductoId($producto);
        $productoVenta->setVentaId($venta);
        $productoVenta->setCantidad($cantidad);
        $productoVenta->setPrecioCosto($precio_costo);
        $productoVenta->setPrecioVenta($precio_venta);
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

    /**
     * @Route("/ventas/eliminar/producto/{id_venta}/{id_producto}", name="v_eliminar_producto_venta")
     */
    public function v_eliminar_producto_venta( Request $request, $id_venta, $id_producto )
    {   
        //echo $id_venta.",".$id_producto."<br><br>";
        $repository = $this->getDoctrine()->getRepository(ProductoVenta::class);
        $producto = $repository->findOneBy([
            'venta_id' => $id_venta,
            'producto_id' => $id_producto
        ]);

        $id_producto_venta = $producto->getId();
        
        $entityManager = $this->getDoctrine()->getManager();
        $producto_venta = $entityManager->getRepository(ProductoVenta::class)->find($id_producto_venta);
      
        $entityManager->remove($producto_venta);
        $entityManager->flush();

        return $this->redirectToRoute('v_editar_venta', array('id' => $id_venta));
    }

    /**
     * @Route("/prueba", name="prueba")
     */
    
    public function prueba( )
    {   
        echo "<br>aaa<br>";

        $id = 1;
        $repository = $this->getDoctrine()->getRepository(Venta::class);
        $venta = $repository->findOneBy(['id' => $id]);
        $productosVenta = $venta->getProductosVenta()->toArray() ;

        foreach($productosVenta as $producto)
        {   
            echo "<pre>";
            echo print_r($producto->getPrecioVenta());
            echo "</pre>";
        }

        $response = new Response('<html>
            <body>
                <h1>Hello Symfony 4 World</h1>
            </body>
        </html>');

        return $response;
    }
}