<?php
namespace App\Cart\Infrastructure\Repository;

use App\Cart\Application\Repository\ProductRepository;
use App\Cart\Domain\Model\Product;
use App\Cart\Domain\ValueObject\ProductoId;
use Doctrine\ORM\EntityManagerInterface;
class DoctrineProductRepository implements ProductRepository
{
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function findById(ProductoId $id): ?Product
    {
        return $this->entityManager->getRepository(Product::class)->find($id);
    }
}