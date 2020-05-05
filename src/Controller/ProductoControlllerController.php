<?php

namespace App\Controller;
 
use App\Entity\Producto; 
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route; 
use App\FormCrear\FormProducto; 
use Symfony\Component\HttpFoundation\Request;  
use Symfony\Component\HttpFoundation\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;


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
        
        // $productos = $repository->findByName('Pin');
        
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
        
        $form = $this->createForm(FormProducto::class, $producto);

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

            $this->addFlash('success', 'Producto creado exitosamente');

            // return $this->redirectToRoute('v_editar_producto', array(
            //     'id' => $task->getId()
            // ));
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
     * @Route("/productos/editar/{id}", name="v_editar_producto")
     */

    public function v_editar_producto( Request $request, $id )
    {   
        $entityManager = $this->getDoctrine()->getManager();
        $producto = $entityManager->getRepository(Producto::class)->find($id);

        if (!$producto) {
            throw $this->createNotFoundException(
                'There are no producto with the following id: ' . $id
            );
        }

        $form = $this->createForm(FormProducto::class, $producto);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) 
        {   

            $task = $form->getData();
            $producto->setNombre($task->getNombre());
            $producto->setDescripcion($task->getDescripcion());
            $producto->setPrecioCosto($task->getPrecioCosto());
            $producto->setPrecioVenta($task->getPrecioVenta());
            $producto->setStock($task->getStock());
            $producto->setCodigo($task->getCodigo());

            $entityManager->flush();
            
            $this->addFlash('success', 'Producto editado exitosamente');
            
            return $this->redirectToRoute('producto_controlller');

        }
        else
        {
            return $this->render('producto_controlller/editar.html.twig', array(
                'id' => $id,
                'form' => $form->createView()
            ));        
        }
    }
  

    /**
     * @Route("/productos/eliminar/{id}", name="eliminar_producto")
     */
    public function eliminar_producto( Request $request, $id )
    {   
        $entityManager = $this->getDoctrine()->getManager();
        $producto = $entityManager->getRepository(Producto::class)->find($id);
        
        if (!$producto) {
            throw $this->createNotFoundException(
                'No product found for id '.$id
            );
        }

        //$entityManager->remove($producto);
        $producto->setEnabled(false); 
        $entityManager->flush();

        $this->addFlash('success', 'Producto eliminado exitosamente');

        return $this->redirectToRoute('producto_controlller');
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


     /**
     * @Route("/productos/traer/stock/{id}", name="find_stock")
     * Productos JSON
     */
    public function find_stock($id)
    {   
        $entityManager = $this->getDoctrine()->getManager();
        $producto = $entityManager->getRepository(Producto::class)->find($id);

        if (!$producto) {
            throw $this->createNotFoundException(
                'There are no producto with the following id: ' . $id
            );
        }
         

        return $this->json($producto->getStock());
      
    } 
    
    /**
     * Creates a new ActionItem entity.
     *
     * @Route("/search", name="ajax_search")
     * @Method("GET")
     */
    public function searchAction(Request $request)
    {
        $requestString = $request->get('term');
        $repository = $this->getDoctrine()->getRepository(Producto::class);
        $productos = $repository->findByName($requestString);

        if(!$productos) {
            $result['entities']['error'] = "keine Einträge gefunden";
        } else 
        { 

            foreach ($productos as $entity){
                $producto['nombre'] = $entity->getNombre();
                $producto['precio_costo'] = $entity->getPrecioCosto();
                $producto['precio_venta'] = $entity->getPrecioVenta(); 
                $producto['codigo'] = $entity->getCodigo();
                $result[]= array(   "id" => $entity->getId() ,  
                                    "value" => "(".$entity->getCodigo().") ".$entity->getNombre(),
                                    "producto" => $producto
                                ); 
            }
            
        }
 
                 

        return new Response(json_encode($result));
    }

    public function getRealEntities($entities){

        // foreach ($entities as $entity){
        //     $producto['nombre'] = $entity->getNombre();
        //     $producto['precio_costo'] = $entity->getPrecioCosto();
        //     $producto['precio_venta'] = $entity->getPrecioVenta();
        //     $informacionProducto[$entity->getId()] = $producto;
        //     $informacionProducto["value"] = $entity->getNombre();
        // }
        
        $informacionProducto[]= array(  "N_ID_PERSONA" => 1 ,  
                                        "value" => "CC",
                                        "producto" => "aaaaaa"
                                    ); 
        
        $informacionProducto[]= array(  "N_ID_PERSONA" => 1 ,  
                                        "value" => "AAAA",
                                        "producto" => "aaaaaa"
                                    ); 

        return $informacionProducto;
    }



}   
