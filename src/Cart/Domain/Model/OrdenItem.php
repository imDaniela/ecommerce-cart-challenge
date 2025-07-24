<?php

namespace App\Cart\Domain\Model;

use App\Cart\Domain\ValueObject\Cantidad;
use App\Cart\Domain\ValueObject\OrdenId;
use App\Cart\Domain\ValueObject\ProductoId;
use App\Repository\OrdenItemRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: OrdenItemRepository::class)]
class OrdenItem
{
   #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'AUTO')]
    #[ORM\Column(type: 'integer')]
    private ?int $id = null;

    #[ORM\JoinColumn(name: 'id_producto', referencedColumnName: 'id')]
    #[ORM\Column(type: 'product_id_vo')]
    private ProductoId $id_producto;

    #[ORM\JoinColumn(name: 'id_orden', referencedColumnName: 'id')]
    #[ORM\Column(type: 'orden_id_vo')]
    private OrdenId $id_orden;

    #[ORM\JoinColumn(name: 'cantidad', referencedColumnName: 'cantidad')]
    #[ORM\Column(type: 'cantidad_vo')]
    private Cantidad $cantidad;

    public function __construct(?int $id, ProductoId $id_producto, OrdenId $id_orden, Cantidad $cantidad)
    {
        $this->id = $id;
        $this->id_producto = $id_producto;
        $this->id_orden = $id_orden;
        $this->cantidad = $cantidad;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getIdProducto(): ?ProductoId
    {
        return $this->id_producto;
    }

    public function setIdProducto(ProductoId $id_producto): static
    {
        $this->id_producto = $id_producto;

        return $this;
    }

    public function getIdOrden(): ?OrdenId
    {
        return $this->id_orden;
    }

    public function setIdOrden(OrdenId $id_orden): static
    {
        $this->id_orden = $id_orden;

        return $this;
    }

    public function getCantidad(): ?Cantidad
    {
        return $this->cantidad;
    }

    public function setCantidad(Cantidad $cantidad): static
    {
        $this->cantidad = $cantidad;

        return $this;
    }
}
