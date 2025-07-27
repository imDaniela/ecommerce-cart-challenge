<?php

namespace App\Cart\Domain\Model;

use App\Cart\Domain\ValueObject\ProductName;
use App\Cart\Domain\ValueObject\ProductoId;
use App\Cart\Domain\ValueObject\ProductPrice;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
class Product
{
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'AUTO')]
    #[ORM\Column(type: 'product_id_vo')]
    private ?ProductoId $id = null;

    #[ORM\Column(type: 'product_name_vo')]
    #[ORM\JoinColumn(name: 'nombre', referencedColumnName: 'nombre')]
    private ?ProductName $nombre = null;

    #[ORM\Column(type: 'product_price_vo', precision: 10, scale: 2)]
    #[ORM\JoinColumn(name: 'precio', referencedColumnName: 'precio')]
    private ?ProductPrice $precio = null;

    public function __construct(?ProductoId $id = null, ?ProductName $nombre = null, ?ProductPrice $precio = null)
    {
        $this->id = $id;
        $this->nombre = $nombre;
        $this->precio = $precio;
    }

    public function getId(): ?ProductoId
    {
        return $this->id;
    }

    public function getNombre(): ?ProductName
    {
        return $this->nombre;
    }

    public function setNombre(ProductName $nombre): static
    {
        $this->nombre = $nombre;

        return $this;
    }

    public function getPrecio(): ?ProductPrice
    {
        return $this->precio;
    }

    public function setPrecio(ProductPrice $precio): static
    {
        $this->precio = $precio;

        return $this;
    }
}
