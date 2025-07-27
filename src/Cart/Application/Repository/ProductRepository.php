<?php
namespace App\Cart\Application\Repository;

use App\Cart\Domain\ValueObject\ProductoId;
use App\Cart\Domain\Model\Product; 

interface ProductRepository
{
    public function findById(ProductoId $id): ?Product;
    public function findAll(): array;
}