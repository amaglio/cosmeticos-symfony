<?php

namespace App\Controller\Admin;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Producto; 
use App\Entity\Venta; 

class ReporteController extends AbstractController
{
    /**
     * @Route("/reporte", name="reporte")
     */
    public function index()
    {   
        // Ventas mensuales 

        // Ventas
        $repository = $this->getDoctrine()->getRepository(Venta::class);
        $ventas = $repository->findCantidadVentasMensual();

        $fechas = array();
        $cantidad_ventas = array();

        foreach($ventas as $venta){
            array_push($fechas, $venta['fecha']);
            array_push($cantidad_ventas, (int)$venta['cantidad']);
        } 

        // Productos
        $repository = $this->getDoctrine()->getRepository(Producto::class);
        $productos = $repository->findTotalProductosVendidos();

        $a_producto = array();
        $cantidad_productos = array();

        foreach($productos as $producto){
            array_push($a_producto, $producto['nombre']);
            array_push($cantidad_productos, (int)$producto['cantidad']);
        } 
 
        return $this->render('reporte/index.html.twig', [
            'controller_name' => 'ReporteController',
            'fechas' => json_encode($fechas),
            'cantidad_ventas' => json_encode($cantidad_ventas),
            'productos' => json_encode($a_producto),
            'cantidad_productos' => json_encode($cantidad_productos)
        ]);
    }
 
    
    /**
     * @Route("/reporte/ventas", name="reporte_ventas")
     */
    public function ventas()
    {   
        // Ventas mensuales 

        $repository = $this->getDoctrine()->getRepository(Venta::class);
        $productos = $repository->findCantidadVentasMensual();
         
        return json_encode($ventas);
    }



}
