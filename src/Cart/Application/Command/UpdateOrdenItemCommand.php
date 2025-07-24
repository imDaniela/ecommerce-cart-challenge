<?php
namespace App\Cart\Application\Command;

class UpdateOrdenItemCommand
{
    public $id;
    public $id_producto;
    public $id_orden;
    public ?int $cantidad;
    public function __construct(int $id, int $id_producto, int $id_orden, ?int $cantidad = null)
    {
        $this->id = $id;
        $this->id_producto = $id_producto;
        $this->id_orden = $id_orden;
        $this->cantidad = $cantidad;
    }
}
