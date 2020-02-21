<?php

namespace App\Controller;

<<<<<<< HEAD
use App\Entity\Producto; 
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route; 
use App\FormCrear\PostType; 
use Symfony\Component\HttpFoundation\Request;  
=======
use App\Entity\Producto;
use App\Repository\ProductoRepository; 
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
>>>>>>> 6cd9971a82d997e1455baeb0ebb657870f7b0e4d



class ProductoControlllerController extends AbstractController  
{
    /**
<<<<<<< HEAD
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
=======
     * @Route("/productos", name="productos_controlller")
     */
    public function index_producto()
    {   
        $productos = $this->getDoctrine()
            ->getRepository(Producto::class)
            ->findBy([
                'enabled' => 1 
            ]);

        var_dump($productos);
            
        return $this->render('producto_controlller/index.html.twig', [
            'controller_name' => 'ProductoControlllerController',
            'productos' => $productos
        ]);
    }

    /**
     * @Route("/producto/crear", name="create_product")
     */
    public function createProduct(ValidatorInterface $validator): Response
>>>>>>> 6cd9971a82d997e1455baeb0ebb657870f7b0e4d
    {
         $producto = new Producto();
        
        $form = $this->createForm(PostType::class, $producto);

<<<<<<< HEAD
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
=======
        $producto = new Producto();
        $producto->setNombre("aaaa");
        $producto->setPrecioCosto(150);
        $producto->setStock(10);


        $errors = $validator->validate($producto);
        
        if (count($errors) > 0) {
            return new Response((string) $errors, 400);
        }
        else
        {

            // tell Doctrine you want to (eventually) save the producto (no queries yet)
            $entityManager->persist($producto);

            // actually executes the queries (i.e. the INSERT query)
            $entityManager->flush();
            return new Response('Saved new product with id '.$producto->getId());
        }

        
    }

    /**
     * @Route("/producto/ver/{id}", name="product_show")
     */
    public function show($id)
    {
        $producto = $this->getDoctrine()
            ->getRepository(Producto::class)
            ->find($id);

        if (!$producto) {
            throw $this->createNotFoundException(
                'No producto found for id '.$id
            );
        }

        return new Response('Check out this great producto: '.$producto->getNombre());
 
    }

    /**
     * @Route("/producto/actualizar/{id}", name="product_update")
     */
    public function update($id)
    {
        $entityManager = $this->getDoctrine()->getManager();
        $producto = $entityManager->getRepository(Producto::class)->find($id);

        if (!$producto) {
            throw $this->createNotFoundException(
                'No product found for id '.$id
            );
        }

        $producto->setNombre('New AAAA name!');
        $entityManager->flush();

        return $this->redirectToRoute('product_show', [
            'id' => $producto->getId()
        ]);
    }

    /**
     * @Route("/producto/delete/{id}", name="delete  _update")
     */
    public function delete($id)
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
        
        return new Response('Eliminado exitosamente' );

    }

 
}
>>>>>>> 6cd9971a82d997e1455baeb0ebb657870f7b0e4d
