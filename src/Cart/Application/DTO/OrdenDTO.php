<?php   
namespace App\Cart\Application\DTO;

class OrdenDTO
{
    public $id;
    public $username;
    public $pagado;
    public function jsonSerialize(): array
    {
        return [
            'id' => $this->id,
            'username' => $this->username,
            'pagado' => $this->pagado,
        ];
    }
}