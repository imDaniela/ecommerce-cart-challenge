<?php
namespace App\Cart\Application\Query;

class GetOrdenByIdQuery
{
    public int $id;

    public function __construct(int $id)
    {
        $this->id = $id;
    }
}