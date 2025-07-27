<?php   
namespace App\Cart\Application\DTO;

class ProductDTO
{
    public $id;
    public $nombre;
    public $precio;

    public function jsonSerialize(): array
    {
        return [
            'id' => $this->id,
            'nombre' => $this->nombre,
            'precio' => $this->precio,
        ];
    }
}