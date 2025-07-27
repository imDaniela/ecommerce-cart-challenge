<?php
namespace App\Cart\Application\Query;

class GetProductByIdQuery
{
    public int $id;

    public function __construct(int $id)
    {
        $this->id = $id;
    }
}