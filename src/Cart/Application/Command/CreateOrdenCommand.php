<?php
namespace App\Cart\Application\Command;

class CreateOrdenCommand
{
    public $username;
    public function __construct(string $username)
    {
        $this->username = $username;
    }
}
