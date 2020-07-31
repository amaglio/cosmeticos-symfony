<?php

namespace App\Entity;

use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ORM\Mapping as ORM;
 

/**
 * @ORM\Entity(repositoryClass="App\Repository\ProductoRepository")
 */
class Producto
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(message="Debe completar el nombre del producto")
     */
    private $nombre;

    /**
     * @ORM\Column(type="string", length=500, nullable=true)
     */
    private $descripcion;

    /** 
     * @ORM\Column(type="float")
     * @Assert\NotBlank(message="Debe completar el precio de costo")
     * @Assert\Positive(message="El precio de costo debe ser positivo") 
     */
    private $precio_costo;

    /**
     * @ORM\Column(type="float", nullable=true)
     * @Assert\NotBlank(message="Debe completar el precio de venta")
     * @Assert\Positive(message="El precio de venta debe ser positivo")  
     */
    private $precio_venta;

    /**
     * @ORM\Column(type="integer")
     * @Assert\NotBlank(message="Debe completar el stock")
     * @Assert\Positive(message="El stock debe ser positivo") 
     */
    private $stock;

    /**
     * @ORM\Column(type="integer", nullable=true)
     * @Assert\NotBlank(message="Debe completar el codigo")
     * @Assert\Positive(message="El codigo debe ser positivo")
     */
    private $codigo;

    /**
     * @ORM\Column(type="boolean")
     */
    private $enabled = true;

    /**
     * @ORM\Column(type="string", length=255, options={"default": "sinfoto.jpg"} )
     */
    private $imagen = "sinfoto.jpg";

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNombre(): ?string
    {
        return $this->nombre;
    }

    public function setNombre(string $nombre): self
    {
        $this->nombre = $nombre;

        return $this;
    }

    public function getDescripcion(): ?string
    {
        return $this->descripcion;
    }

    public function setDescripcion(?string $descripcion): self
    {
        $this->descripcion = $descripcion;

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

    public function setPrecioVenta(?float $precio_venta): self
    {
        $this->precio_venta = $precio_venta;

        return $this;
    }

    public function getStock(): ?int
    {
        return $this->stock;
    }

    public function setStock(int $stock): self
    {
        $this->stock = $stock;

        return $this;
    }

    public function getCodigo(): ?int
    {
        return $this->codigo;
    }

    public function setCodigo(?int $codigo): self
    {
        $this->codigo = $codigo;

        return $this;
    }

    public function getEnabled(): ?bool
    {
        return $this->enabled;
    }

    public function setEnabled(bool $enabled): self
    {
        $this->enabled = $enabled;

        return $this;
    }

    public function __toString()
    {
        return $this->nombre;
    }

    public function isStock($cantidad)
    {
       return ( $this->stock >= $cantidad ?  true : false) ;
    }

    public function getImagen(): ?string
    {
        return $this->imagen;
    }

    public function setImagen(?string $imagen): self
    {
        $this->imagen = $imagen;

        return $this;
    }
}
