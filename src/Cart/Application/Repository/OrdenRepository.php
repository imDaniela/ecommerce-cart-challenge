<?php
namespace App\Cart\Application\Repository;

use App\Cart\Domain\Model\Orden;
use App\Cart\Domain\ValueObject\OrdenId;
interface OrdenRepository
{
    public function save(Orden $orden): void;
    public function findById(OrdenId $id): ?Orden;
    public function setAsPagada(Orden $orden): void;
}