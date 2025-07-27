<?php   
namespace App\Cart\Application\DTO;

class OrdenItemDTO implements \JsonSerializable
{
    public $id;
    public $id_producto;
    public $nombre_producto;
    public $id_orden;
    public $cantidad;
    public $precio;

    public function jsonSerialize(): array
    {
        return [
            'id' => $this->id,
            'id_producto' => $this->id_producto,
            'nombre_producto' => $this->nombre_producto,
            'precio' => $this->precio ?? 0, // Default to 0 if not set
            'id_orden' => $this->id_orden,
            'cantidad' => $this->cantidad,
        ];
    }
}