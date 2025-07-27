<?php
namespace App\Cart\Application\Command;

class SetOrdenAsPagadaCommand
{
    public $id;
    public function __construct(int $id)
    {
        $this->id = $id;
    }
}
