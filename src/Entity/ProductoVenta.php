<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ProductoVentaRepository")
 */
class ProductoVenta
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Producto")
     * @ORM\JoinColumn(nullable=false) 
     */
    private $producto_id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Venta" )
     * @ORM\JoinColumn(nullable=false)
     */
    private $venta_id;

    /**
     * @ORM\Column(type="integer")
     */
    private $cantidad;

    /**
     * @ORM\Column(type="float")
     */
    private $precio_costo;

    /**
     * @ORM\Column(type="float")
     */
    private $precio_venta;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getProductoId(): ?Producto
    {
        return $this->producto_id;
    }

    public function setProductoId(?Producto $producto_id): self
    {
        $this->producto_id = $producto_id;

        return $this;
    }

    public function getVentaId(): ?Venta
    {
        return $this->venta_id;
    }

    public function setVentaId(?Venta $venta_id): self
    {
        $this->venta_id = $venta_id;

        return $this;
    }

    public function getCantidad(): ?int
    {
        return $this->cantidad;
    }

    public function setCantidad(int $cantidad): self
    {
        $this->cantidad = $cantidad;

        return $this;
    }

    public function getPrecioCosto(): ?float
    {
        return $this->precio_costo;
    }

    public function setPrecioCosto(float $precio_costo): self
    {
        $this->precio_costo = $precio_costo;

        return $this;
    }

    public function getPrecioVenta(): ?float
    {
        return $this->precio_venta;
    }

    public function setPrecioVenta(float $precio_venta): self
    {
        $this->precio_venta = $precio_venta;

        return $this;
    }
}
