<?php

namespace App\Controller;

use App\Entity\Producto;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use App\FormCrear\FormProducto;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Dompdf\Dompdf;
use Dompdf\Options;


class ProductoController extends AbstractController
{
    /**
     * @Route("/admin/productos", name="admin_productos")
     */
    public function index()
    {
        
        $repository = $this->getDoctrine()->getRepository(Producto::class);

        $productos = $repository->findBy(
            ['enabled' => 1]
        );
 
        return $this->render('admin/producto/index.html.twig', [
            'controller_name' => 'Productos',
            'productos' => $productos
        ]);
    }


    /**
     * @Route("/admin/productos/crear", name="crear_producto")
     */
    public function crear_producto(Request $request, SluggerInterface $slugger)
    { 
        $producto = new Producto();

        $form = $this->createForm(FormProducto::class, $producto);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $task = $form->getData();

            $imagenFile = $form->get('imagen')->getData();

            if ($imagenFile) {
                $originalFilename = pathinfo($imagenFile->getClientOriginalName(), PATHINFO_FILENAME);
                // this is needed to safely include the file name as part of the UR

                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename . '-' . uniqid() . '.' . $imagenFile->guessExtension();

                // Move the file to the directory where brochures are stored
                try {
                    $imagenFile->move(
                        $this->getParameter('imagen_directory'),
                        $newFilename
                    );
                } catch (FileException $e) {
                    // ... handle exception if something happens during file upload
                }

                // updates the 'imagenFilename' property to store the PDF file name
                // instead of its contents
                $producto->setImagen($newFilename);
            }

            // ... perform some action, such as saving the task to the database
            // for example, if Task is a Doctrine entity, save it!
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($task);
            $entityManager->flush();

            $this->addFlash('success', 'Producto creado exitosamente');

             
            return $this->redirectToRoute('admin_productos');
        } else {
            return $this->render('admin/producto/crear.html.twig', array(
                'form' => $form->createView(),
            ));
        }
    }

    /**
     * @Route("/admin/productos/editar/{id}", name="editar_producto")
     */

    public function editar_producto(Request $request, $id, SluggerInterface $slugger)
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

        if ($form->isSubmitted() && $form->isValid()) {

            $task = $form->getData();
            $producto->setNombre($task->getNombre());
            $producto->setDescripcion($task->getDescripcion());
            $producto->setPrecioCosto($task->getPrecioCosto());
            $producto->setPrecioVenta($task->getPrecioVenta());
            $producto->setStock($task->getStock());
            $producto->setCodigo($task->getCodigo());

            $imagenFile = $form->get('imagen')->getData();

            if ($imagenFile) {
                $originalFilename = pathinfo($imagenFile->getClientOriginalName(), PATHINFO_FILENAME);
                // this is needed to safely include the file name as part of the UR

                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename . '-' . uniqid() . '.' . $imagenFile->guessExtension();

                // Move the file to the directory where brochures are stored
                try {
                    $imagenFile->move(
                        $this->getParameter('imagen_directory'),
                        $newFilename
                    );
                } catch (FileException $e) {
                    // ... handle exception if something happens during file upload
                }

                // updates the 'imagenFilename' property to store the PDF file name
                // instead of its contents
                $producto->setImagen($newFilename);
            }

            $entityManager->flush();

            $this->addFlash('success', 'Producto editado exitosamente');

            return $this->redirectToRoute('admin_productos');
        } else {
            return $this->render('admin/producto/editar.html.twig', array(
                'id' => $id,
                'form' => $form->createView(),
                'imagen' => $producto->getImagen(),

            ));
        }
    }


    /**
     * @Route("/admin/productos/eliminar/{id}", name="eliminar_producto")
     */
    public function eliminar_producto(Request $request, $id)
    {
        $entityManager = $this->getDoctrine()->getManager();
        $producto = $entityManager->getRepository(Producto::class)->find($id);

        if (!$producto) {
            throw $this->createNotFoundException(
                'No product found for id ' . $id
            );
        }

         $producto->setEnabled(false);
        $entityManager->flush();

        $this->addFlash('success', 'Producto eliminado exitosamente');

        return $this->redirectToRoute('admin_productos');
    }
 
    /**
     * @Route("/productos_pdf_nuevo", name="productos_pdf_nuevo")
     */
    public function productos_pdf_nuevo()
    {   
        $repository = $this->getDoctrine()->getRepository(Producto::class);

        $productos = $repository->findBy(
            ['enabled' => 1]
        );
 
        return $this->render('admin/producto/pdf_view.html.twig', [
            'controller_name' => 'Productos',
            'productos' => $productos
        ]);
    }
}
