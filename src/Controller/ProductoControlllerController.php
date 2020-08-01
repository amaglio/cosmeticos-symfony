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

require_once("/var/www/html/cosmeticos-symfony/vendor/tecnickcom/tcpdf/tcpdf.php");


class ProductoControlllerController extends AbstractController
{
    /**
     * @Route("/productos", name="producto_controlller")
     * Productos habilitados
     */
    public function index()
    {
        // echo $this->get('kernel')->getProjectDir(); 


        $repository = $this->getDoctrine()->getRepository(Producto::class);

        $productos = $repository->findBy(
            ['enabled' => 1]
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
    public function v_crear_producto(Request $request, SluggerInterface $slugger)
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

            // return $this->redirectToRoute('v_editar_producto', array(
            //     'id' => $task->getId()
            // ));
            return $this->redirectToRoute('producto_controlller');
        } else {
            return $this->render('producto_controlller/crear.html.twig', array(
                'form' => $form->createView(),
            ));
        }
    }

    /**
     * @Route("/productos/editar/{id}", name="v_editar_producto")
     */

    public function v_editar_producto(Request $request, $id, SluggerInterface $slugger)
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

            return $this->redirectToRoute('producto_controlller');
        } else {
            return $this->render('producto_controlller/editar.html.twig', array(
                'id' => $id,
                'form' => $form->createView(),
                'imagen' => $producto->getImagen(),

            ));
        }
    }


    /**
     * @Route("/productos/eliminar/{id}", name="eliminar_producto")
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
            ['enabled' => 1]
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

        if (!$productos) {
            $result['entities']['error'] = "keine Einträge gefunden";
        } else {

            foreach ($productos as $entity) {
                $producto['nombre'] = $entity->getNombre();
                $producto['precio_costo'] = $entity->getPrecioCosto();
                $producto['precio_venta'] = $entity->getPrecioVenta();
                $producto['codigo'] = $entity->getCodigo();
                $result[] = array(
                    "id" => $entity->getId(),
                    "value" => "(" . $entity->getCodigo() . ") " . $entity->getNombre(),
                    "producto" => $producto
                );
            }
        }



        return new Response(json_encode($result));
    }

    public function getRealEntities($entities)
    {

        // foreach ($entities as $entity){
        //     $producto['nombre'] = $entity->getNombre();
        //     $producto['precio_costo'] = $entity->getPrecioCosto();
        //     $producto['precio_venta'] = $entity->getPrecioVenta();
        //     $informacionProducto[$entity->getId()] = $producto;
        //     $informacionProducto["value"] = $entity->getNombre();
        // }

        $informacionProducto[] = array(
            "N_ID_PERSONA" => 1,
            "value" => "CC",
            "producto" => "aaaaaa"
        );

        $informacionProducto[] = array(
            "N_ID_PERSONA" => 1,
            "value" => "AAAA",
            "producto" => "aaaaaa"
        );

        return $informacionProducto;
    }

    /**
     * @Route("/productos_pdf", name="productos_pdf")
     * Productos habilitados
     */
    public function productos_pdf()
    {
        // Configure Dompdf according to your needs


        $pdfOptions = new Options();
        $pdfOptions->set('defaultFont', 'Arial');
        $pdfOptions->set('isRemoteEnabled', true);

        // Instantiate Dompdf with our options
        $dompdf = new Dompdf($pdfOptions);

        $repository = $this->getDoctrine()->getRepository(Producto::class);

        $productos = $repository->findBy(
            ['enabled' => 1]
        );


        // Retrieve the HTML generated in our twig file
        $html = $this->renderView('producto_controlller/pdf.html.twig', [
            'title' => "Welcome to our PDF Test",
            'productos' => $productos,
            'root_path' => $this->getParameter('webDir')
        ]);

        // Load HTML to Dompdf
        $dompdf->loadHtml($html);

        // (Optional) Setup the paper size and orientation 'portrait' or 'portrait'
        $dompdf->setPaper('A4', 'portrait');

        // Render the HTML as PDF
        $dompdf->render();

        // Output the generated PDF to Browser (force download)
        $dompdf->stream("mypdf.pdf", [
            "Attachment" => true
        ]);
    }


    /**
     * @Route("/productos_pdf_esquema", name="productos_pdf")
     * Productos habilitados
     */

    public function productos_pdf_esquema()
    {   
        // Repositorio

        $repository = $this->getDoctrine()->getRepository(Producto::class);

        $productos = $repository->findBy(
            ['enabled' => 1]
        );

        // Configure Dompdf according to your needs
        
        $pdfOptions = new Options();
        $pdfOptions->set('defaultFont', 'Arial');
        $pdfOptions->set('isRemoteEnabled', true); 
        $pdfOptions->set('isHtml5ParserEnabled', true);

        // Instantiate Dompdf with our options
        $dompdf = new Dompdf($pdfOptions);

        //var_dump($productos);

        // for ($i=0; $i < count($productos) ; $i++) 
        // { 
        //     echo  $productos[$i]['imagen']."<br>";
        // }

        foreach ($productos as $key => $value) 
        {
            $image = './uploads/imagenes/'.$value->getImagen();

            //echo mime_content_type($image);
    
            // Read image path, convert to base64 encoding
            $imageData = base64_encode(file_get_contents($image));
    
            // Format the image SRC:  data:{mime};base64,{data};
            $src = 'data:'.mime_content_type($image).';base64,'.$imageData;

            $value->setImagen($src);
        }
        
        //var_dump($productos);

        // Retrieve the HTML generated in our twig file
        $html =  $this->renderView('producto_controlller/pdf.html.twig', [
            'title' => "Welcome to our PDF Test",
            'productos' => $productos,
            'src' => $src,
            'root_path' => $this->getParameter('webDir')
        ]);


        // Load HTML to Dompdf
        $dompdf->loadHtml($html);

        // (Optional) Setup the paper size and orientation 'portrait' or 'portrait'
        $dompdf->setPaper('A4', 'portrait');

        // Render the HTML as PDF
        $dompdf->render();

        $pdf = $dompdf->output();

        // Output the generated PDF to Browser (force download)
        $dompdf->stream("catalogo.pdf");
    }

    //  public function productos_pdf_esquema()
        // {   
        //      // Configure Dompdf according to your needs

        //      $pdfOptions = new Options();
        //      $pdfOptions->set('defaultFont', 'Arial');
        //      $pdfOptions->set('isRemoteEnabled',true); 

        //      $pdfOptions->setIsRemoteEnabled(true);   

        //      // Instantiate Dompdf with our options
        //      $dompdf = new Dompdf($pdfOptions);

        //      $contxt = stream_context_create([ 
        //         'ssl' => [ 
        //             'verify_peer' => FALSE, 
        //             'verify_peer_name' => FALSE,
        //             'allow_self_signed'=> TRUE
        //         ] 
        //     ]);
        //     $dompdf->setHttpContext($contxt);

        //      $repository = $this->getDoctrine()->getRepository(Producto::class);

        //      $productos = $repository->findBy(
        //          ['enabled' => 1 ] 
        //      ); 


        //      // Retrieve the HTML generated in our twig file
        //      $html = $this->renderView('producto_controlller/pdf.html.twig', [
        //          'title' => "Welcome to our PDF Test",
        //          'productos' => $productos,
        //          'root_path' => $this->getParameter('webDir') 
        //      ]);


        //      // Load HTML to Dompdf
        //      $dompdf->loadHtml($html);

        //      // (Optional) Setup the paper size and orientation 'portrait' or 'portrait'
        //      $dompdf->setPaper('A4', 'portrait');

        //      // Render the HTML as PDF
        //      $dompdf->render();

        //      $pdf = $dompdf->output();



        //      // Output the generated PDF to Browser (force download)
        //      $dompdf->stream("catalogo.pdf");

        //     //  return $this->render('producto_controlller/pdf.html.twig', array(
        //     //     'title' => "Welcome to our PDF Test",
        //     //     'productos' => $productos,
        //     //     'root_path' => $this->getParameter('webDir') 

        //     // ));      

    // }   

    /**
     * @Route("/productos_pdf_nuevo", name="productos_pdf_nuevo")
     * Productos habilitados
     */
    public function productos_pdf_nuevo()
    {   
        $image = './uploads/imagenes/sinfoto.jpg';

        echo mime_content_type($image);

        // Read image path, convert to base64 encoding
        $imageData = base64_encode(file_get_contents($image));

        // Format the image SRC:  data:{mime};base64,{data};
        $src = 'data:'.mime_content_type($image).';base64,'.$imageData;

        // Echo out a sample image
        echo '<img src="'.$src.'">';
    }
}
