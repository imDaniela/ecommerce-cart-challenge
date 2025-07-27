<?php   
namespace App\Cart\Application\DTO;
use App\Cart\Domain\Model\OrdenItem;

class OrdenItemDTO implements \JsonSerializable
{
    public $id;
    public $id_producto;
    public $nombre_producto;
    public $id_orden;
    public $cantidad;

    public function jsonSerialize(): array
    {
        return [
            'id' => $this->id,
            'id_producto' => $this->id_producto,
            'nombre_producto' => $this->nombre_producto,
            'id_orden' => $this->id_orden,
            'cantidad' => $this->cantidad,
        ];
    }
}