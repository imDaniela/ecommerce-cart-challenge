<?php
namespace App\Cart\Application\Query;

class GetOrdenItemByOrdenIdQuery
{
    public int $id_orden;

    public function __construct(int $id_orden)
    {
        $this->id_orden = $id_orden;
    }
}