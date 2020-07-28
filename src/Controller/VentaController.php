<?php

namespace App\Controller;

use App\Entity\Venta; 
use App\Entity\Producto;
use App\Entity\ProductoVenta; 
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use App\FormCrearVenta\FormVenta; 
use App\FormVentaProducto\FormVentaProducto; 
use Symfony\Component\HttpFoundation\Request; 
use Symfony\Component\HttpFoundation\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
 

class VentaController extends AbstractController
{   
    /**
     * @Route("/", name="home")
     */
    public function home()
    {   
        $repository = $this->getDoctrine()->getRepository(Venta::class);
  
        $ventas = $repository->findBy(
            ['enabled' => 1 ] 
        ); 

        // $ventas = $repository->findByDate(date("Y/m/d"),date("Y/m/d")); 

        $repository = $this->getDoctrine()->getRepository(Producto::class);

        $productos = $repository->findBy(
            ['enabled' => 1 ] 
        ); 

        return $this->render('venta/home.html.twig', [
            'controller_name' => 'VentaController',
            'ventas' => $ventas,
            'productos' => $productos
        ]);
    }

    /**
     * @Route("/ventas", name="ventas")
     */
    public function index()
    {   
        $repository = $this->getDoctrine()->getRepository(Venta::class);

        // $ventas = $repository->findAll(); 
        $ventas = $repository->findBy(
            ['enabled' => 1 ] 
        ); 

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
        $form = $this->createForm(FormVenta::class, $venta);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) 
        {
            // $form->getData() holds the submitted values
            // but, the original `$task` variable has also been updated
            $venta_data = $form->getData();
 
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($venta_data);
            $entityManager->flush();
            //$venta_data->getId();
            // return $this->redirectToRoute('/ventas/editar/2');

            $this->addFlash('success', 'Venta creada exitosamente');

            return $this->redirectToRoute('v_editar_venta', array(
                'id' => $venta_data->getId()
            ));
        }
        else
        {   

            return $this->render('venta/crear.html.twig', array(
                'form' => $form->createView(),
            ));

        }
        
        
    }
    
    /**
     * @Route("/ventas/editar/{id}", name="v_editar_venta"  )
     * @ParamConverter("form", class="App\FormVentaProducto\FormVentaProducto")
     */
    public function v_editar_venta( Request $request, $id, FormVentaProducto $form = NULL )
    {   
  
        $entityManager = $this->getDoctrine()->getManager(); 
        $venta = $entityManager->getRepository(Venta::class)->find($id);

        if (!$venta) {
            throw $this->createNotFoundException(
                'There are no producto with the following id: ' . $id
            );
        } 
        $form_venta = $this->createForm(FormVenta::class, $venta);
        $form_venta->handleRequest($request);
        
        // Modifico la venta
        if ($form_venta->isSubmitted() && $form_venta->isValid()) 
        {   
                
            $form_data = $form_venta->getData();
  
            $venta->setNombre($form_data->getNombre());
            $venta->setFecha($form_data->getFecha()); 
            $venta->setTelefono($form_data->getTelefono());
            $venta->setEmail($form_data->getEmail()); 

            $entityManager->flush();

            $this->addFlash('success', 'Venta editada exitosamente');

            //return $this->redirectToRoute('ventas');

            return $this->redirectToRoute('v_editar_venta', array(
                'id' => $id
            ));
        }
        else
        {   
            // Si no edito, pregunto si quiere agregar un producto

            $productoVenta = new ProductoVenta();
            $productoVenta->setVentaId($venta);  
           
            // if( $request->request->get('post_type_producto_venta')["producto_id"] )
            // {
            //     $producto_id = $request->request->get('post_type_producto_venta')["producto_id"];
            //     $repository = $this->getDoctrine()->getRepository(Producto::class);
            //     $producto = $repository->findOneBy(['id' => $producto_id]);
            //     $productoVenta->setPrecioCosto($producto->getPrecioCosto());
            //     $productoVenta->setPrecioVenta($producto->getPrecioVenta()); 
            //     echo "etreo";
            // } 
            
            $form_producto_venta = $this->createForm(FormVentaProducto::class, $productoVenta);
            $form_producto_venta->handleRequest($request);

            // Ingreso un producto a la venta

            if ($form_producto_venta->isSubmitted() && $form_producto_venta->isValid()) 
            {   
                $producto_venta = $form_producto_venta->getData();

            
 
                $productoVenta->setVentaId($venta);
                
                $producto_id = $request->request->get('form_venta_producto')["producto_id"];
                echo "producto_id: ".$producto_id;
                $repository = $this->getDoctrine()->getRepository(Producto::class);
                $producto = $repository->findOneBy(['id' => $producto_id]);

                $cantidad = $request->request->get('form_venta_producto')["cantidad"];
                
                echo "Cantidad: ".$cantidad;
            
                if( $producto->isStock($cantidad) ) // Hay stock
                {
                    $productoVenta->setPrecioCosto($producto->getPrecioCosto());
                    $productoVenta->setPrecioVenta($producto->getPrecioVenta()); 
                    $entityManager = $this->getDoctrine()->getManager();
                    $entityManager->persist($producto_venta);
                    $entityManager->flush(); 

                    $producto->setStock( $producto->getStock() - $cantidad );
                    $entityManager = $this->getDoctrine()->getManager();
                    $entityManager->persist($producto);
                    $entityManager->flush(); 

                    // $producto->setStock( $producto->getStock() - $cantidad );
                    // $entityManager = $this->getDoctrine()->getManager();
                    // $entityManager->persist($producto);
                    // $entityManager->flush(); 
                    $this->addFlash('success', 'Producto agregado exitosamente');
                }
                else
                {
                    $this->addFlash('danger', 'El producto no tiene stock suficiente');

                } 
                 
                return $this->redirectToRoute('v_editar_venta', array(
                    'id' => $id
                )); 
            }
            else // Muestra la venta
            {   
                $productosVenta = $venta->getProductosVenta()->toArray() ;
                
                $ProductoVenta = new ProductoVenta();
                $ProductoVenta->setVentaId($venta); 
                $ProductoVenta->setPrecioVenta(0);
                $ProductoVenta->setPrecioCosto(0);
                $formProductoVenta = $this->createForm(  FormVentaProducto::class, 
                                                         $ProductoVenta 
                                                    );
                
                $repository = $this->getDoctrine()->getRepository(Producto::class);
                $listadoProductos = $repository->findAll(); 
                
                return $this->render('venta/editar.html.twig', array(
                    'id' => $id,
                    'venta' => $venta,
                    'form' => $form_venta->createView(),
                    'productos' => $productosVenta,
                    'listadoProductos' => $listadoProductos,
                    'form_producto_venta' => $formProductoVenta->createView()
                ));     
            }
 
            

      
                
                                                    
                //$formProductoVenta->get('venta_id')->setData($id);
            // }
            // else
            // {
            //     echo "Form enviado ";
            //     $formProductoVenta = $form;
            // }
            
            //$formProductoVenta = $form;
            
           
        }
 
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
 
        $venta->setEnabled(false); 
        $entityManager->flush();

        $this->addFlash('success', 'Venta eliminada exitosamente');

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
        
        $form = $this->createForm(FormVentaProducto::class, $productoVenta);
         

        if ($form->isSubmitted() && $form->isValid()) 
        { 
            $response = new Response('<html>
                                            <body>
                                                <h1>Valido</h1>
                                            </body>
                                        </html>');

        }
        else
        {
            $response = new Response('<html>
                                            <body>
                                                <h1>IN Valido</h1>
                                            </body>
                                        </html>');
        }
        
    
    }

     /**
     * @Route("/ventas/crear-producto-venta", name="v_crear_producto_venta")
     */
    public function v_crear_producto_venta(Request $request)
    {
        $productoVenta = new ProductoVenta();
        $form = $this->createForm(FormVentaProducto::class, $productoVenta);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) 
        {   
            $producto_venta = $form->getData();

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($producto_venta);
            $entityManager->flush(); 

            $this->addFlash('success', 'Producto agregado exitosamente');

            return $this->redirectToRoute('v_editar_venta', array(
                'id' => $producto_venta->getId()
            )); 
        }
        else
        {   

            return $this->render('venta/crear-producto-venta.html.twig', array(
                'form_producto_venta' => $form->createView(),
            ));

        } 

    }






        // $venta_id = $request->request->get('post_type_producto_venta')["venta_id"];
        // $producto_id = $request->request->get('post_type_producto_venta')["producto_id"];
        // $cantidad = $request->request->get('post_type_producto_venta')["cantidad"];
        // $precio_costo = $request->request->get('post_type_producto_venta')["precio_costo"];
        // $precio_venta = $request->request->get('post_type_producto_venta')["precio_venta"];

        // $repository = $this->getDoctrine()->getRepository(Producto::class);
        // $producto = $repository->findOneBy(['id' => $producto_id]);

        // $repository = $this->getDoctrine()->getRepository(Venta::class);
        // $venta = $repository->findOneBy(['id' => $venta_id]);

        // $productoVenta = new ProductoVenta();

        // $productoVenta->setProductoId($producto);
        // $productoVenta->setVentaId($venta);
        // $productoVenta->setCantidad($cantidad);
        // $productoVenta->setPrecioCosto($precio_costo);
        // $productoVenta->setPrecioVenta($precio_venta);
        
        // $form = $this->createForm(FormVentaProducto::class, $productoVenta);

        // $form->handleRequest($request);

        // if ($form->isSubmitted() && $form->isValid()) 
        // {   
        //     $response = new Response('<html>
        //                                 <body>
        //                                     <h1>Valido</h1>
        //                                 </body>
        //                             </html>');
           

        //     // $form->getData() holds the submitted values
        //     // but, the original `$task` variable has also been updated
        //     // $venta_data = $form->getData();
 
        //     // $entityManager = $this->getDoctrine()->getManager();
        //     // $entityManager->persist($venta_data);
        //     // $entityManager->flush();
        //     // //$venta_data->getId();
        //     // // return $this->redirectToRoute('/ventas/editar/2');

        //     // $this->addFlash('success', 'Venta creada exitosamente');

        //     // return $this->redirectToRoute('v_editar_venta', array(
        //     //     'id' => $venta_data->getId()
        //     // ));
        // }
        // else
        // {   
        //     $response = new Response('<html>
        //                                 <body>
        //                                     <h1>In Valido</h1>
        //                                 </body>
        //                             </html>');
        //     // return $this->render('venta/crear.html.twig', array(
        //     //     'form' => $form->createView(),
        //     // ));

        // }


        // return $response;

        // $venta_id = $request->request->get('post_type_producto_venta')["venta_id"];
        // $producto_id = $request->request->get('post_type_producto_venta')["producto_id"];
        // $cantidad = $request->request->get('post_type_producto_venta')["cantidad"];
        // $precio_costo = $request->request->get('post_type_producto_venta')["precio_costo"];
        // $precio_venta = $request->request->get('post_type_producto_venta')["precio_venta"];

        // $repository = $this->getDoctrine()->getRepository(Producto::class);
        // $producto = $repository->findOneBy(['id' => $producto_id]);

        // $repository = $this->getDoctrine()->getRepository(Venta::class);
        // $venta = $repository->findOneBy(['id' => $venta_id]);


        // $productoVenta = new ProductoVenta();

        // $productoVenta->setProductoId($producto);
        // $productoVenta->setVentaId($venta);
        // $productoVenta->setCantidad($cantidad);
        // $productoVenta->setPrecioCosto($precio_costo);
        // $productoVenta->setPrecioVenta($precio_venta);
        // $entityManager = $this->getDoctrine()->getManager();
        // $entityManager->persist($productoVenta);
        // $entityManager->flush();

        
        // //$ProductoVenta = new ProductoVenta();
        // $formProductoVenta = $this->createForm(     FormVentaProducto::class, 
        //                                             $productoVenta, 
        //                                             array(
        //                                                 'action' => $this->generateUrl('agregar_producto_venta') 
        //                                             )
        //                                         );
                                                
        // $formProductoVenta->get('venta_id')->setData($venta_id);

        // $response = new Response('<html>
        //         <body>
        //             <h1>Hello Symfony 4 World</h1>
        //         </body>
        //     </html>');
        // return $response;
        
        // // $this->addFlash('formProductoVenta', $formProductoVenta );

        // //return $this->redirectToRoute('ventas');
        
        // return $this->redirectToRoute('v_editar_venta', array(
        //     'id' => $venta_id,
        //     'form' => $formProductoVenta
        // ));


        //var_dump($request->query);
        
        /*
        $formProductoVenta = $this->createForm(FormVentaProducto::class, $ProductoVenta, array(
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

        $this->addFlash('success', 'Producto eliminado de la venta exitosamente' );

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