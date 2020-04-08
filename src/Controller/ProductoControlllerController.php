<?php

namespace App\Controller;
 
use App\Entity\Producto; 
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route; 
use App\FormCrear\PostType; 
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

        // $productos = $repository->findBy(
        //     ['enabled' => 1 ] 
        // ); 
        
        $productos = $repository->findAllOrderedByName();
        
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
     * @Route("/productos/editar/{id}", name="v_editar_producto")
     */
    public function v_editar_producto( Request $request, $id )
    {   
        $producto = new Producto();
        $form = $this->createForm(PostType::class, $producto);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) 
        {   
            $entityManager = $this->getDoctrine()->getManager();
            
            $task = $form->getData();

            var_dump($task->getNombre());

            $producto = $entityManager->getRepository(Producto::class)->find($id);

            $producto->setNombre($task->getNombre());
            $producto->setDescripcion($task->getDescripcion());
            $producto->setPrecioCosto($task->getPrecioCosto());
            $producto->setPrecioVenta($task->getPrecioVenta());
            $producto->setStock($task->getStock());
            $producto->setCodigo($task->getCodigo());

            $entityManager->flush();

            return $this->redirectToRoute('producto_controlller');

            /*$producto->setName('New product name!');*/



            /*
            $task = $form->getData();
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($task);
            $entityManager->flush();

            return $this->redirectToRoute('producto_controlller');*/
            //echo "UPDATE";
        }
        else
        {

            $repository = $this->getDoctrine()->getRepository(Producto::class);
            $producto = $repository->findOneBy(['id' => $id]);
            $form = $this->createForm(PostType::class, $producto);
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

        $entityManager->remove($producto);
        $entityManager->flush();

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
     * Creates a new ActionItem entity.
     *
     * @Route("/search", name="ajax_search")
     * @Method("GET")
     */
    public function searchAction(Request $request)
    {
        // $em = $this->getDoctrine()->getManager();

        // $requestString = $request->get('q');

        // $entities =  $em->getRepository('AppBundle:Entity')->findEntitiesByString($requestString);

        // if(!$entities) {
        //     $result['entities']['error'] = "keine Einträge gefunden";
        // } else {
        //     $result['entities'] = $this->getRealEntities($entities);
        // }

        // return new Response(json_encode($result));
        
        $requestString = $request->get('q');
        $repository = $this->getDoctrine()->getRepository(Producto::class);
        // $productos = $repository->findProductoByString($requestString); 
        // var_dump($productos);
        $productos = $repository->findBy(
            ['enabled' => 1 ] 
        ); 

        if(!$productos) {
            $result['entities']['error'] = "keine Einträge gefunden";
        } else {
            $result['entities'] =  $productos;
        }


        return new Response(json_encode($result));
    }

    public function getRealEntities($entities){

        // foreach ($entities as $entity){
        //     $realEntities[$entity->getId()] = $entity->getFoo();
        // }

        // return $realEntities;
    }



}   
