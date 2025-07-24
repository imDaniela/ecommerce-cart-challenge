<?php
namespace App\Cart\Application\Command;

class DeleteOrdenItemCommand
{
    public $id;
    public function __construct(int $id)
    {
        $this->id = $id;
    }
}
