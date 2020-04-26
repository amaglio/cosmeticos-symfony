<?php

namespace App\Entity;

use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\VentaRepository")
 */
class Venta
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="date" )
     * @Assert\NotBlank(message="Debe completar la fecha")
     *  
     */
    private $fecha;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(message="Debe completar el nombre del cliente")
     */
    private $nombre;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $telefono;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Assert\Email(  message = "The email '{{ value }}' is not a valid email." )
     * 
     */
    private $email;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\ProductoVenta", mappedBy="venta_id")
     */
    private $productos_venta;

    /**
     * @ORM\Column(type="boolean")
     */
    private $enabled = true;

    public function __construct()
    {
        $this->productos_venta = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFecha(): ?\DateTimeInterface
    {
        return $this->fecha;
    }

    public function setFecha(\DateTimeInterface $fecha): self
    {
        $this->fecha = $fecha;

        return $this;
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

    public function getTelefono(): ?string
    {
        return $this->telefono;
    }

    public function setTelefono(?string $telefono): self
    {
        $this->telefono = $telefono;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(?string $email): self
    {
        $this->email = $email;

        return $this;
    } 
    /**
     * @return Collection|ProductoVenta[]
     */
    public function getProductosVenta(): Collection
    {
        return $this->productos_venta;
    }

    public function getTotalVenta() 
    {   
        $total = 0;
        $productos = $this->productos_venta->toArray();

        foreach($productos as $producto)
        {
            $total += $producto->getPrecioVenta() * $producto->getCantidad() ;
        }

        return $total;
    }

    public function getTotalCostoVenta() 
    {   
        $total = 0;
        $productos = $this->productos_venta->toArray();

        foreach($productos as $producto)
        {
            $total += $producto->getPrecioCosto() * $producto->getCantidad() ;
        }

        return $total;
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

    // public function addCantidad(ProductoVenta $cantidad): self
    // {
    //     if (!$this->cantidad->contains($cantidad)) {
    //         $this->cantidad[] = $cantidad;
    //         $cantidad->setVentaId($this);
    //     }

    //     return $this;
    // }

    // public function removeCantidad(ProductoVenta $cantidad): self
    // {
    //     if ($this->cantidad->contains($cantidad)) {
    //         $this->cantidad->removeElement($cantidad);
    //         // set the owning side to null (unless already changed)
    //         if ($cantidad->getVentaId() === $this) {
    //             $cantidad->setVentaId(null);
    //         }
    //     }

    //     return $this;
    // }
}
