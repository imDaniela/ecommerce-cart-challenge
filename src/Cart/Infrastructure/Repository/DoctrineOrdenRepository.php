<?php
namespace App\Cart\Infrastructure\Repository;

use App\Cart\Application\Repository\OrdenRepository;
use App\Cart\Domain\Model\Orden;
use App\Cart\Domain\ValueObject\OrdenId;
use Doctrine\ORM\EntityManagerInterface;
class DoctrineOrdenRepository implements OrdenRepository
{
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }
    public function save(Orden $orden): void
    {
        $this->entityManager->persist($orden);
        $this->entityManager->flush();
    }
    public function findById(OrdenId $id): ?Orden
    {
        return $this->entityManager->getRepository(Orden::class)->find($id);
    }
}