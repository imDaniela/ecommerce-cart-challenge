<?php
namespace App\Cart\Infrastructure\Repository;

use App\Cart\Application\Repository\OrdenItemRepository;
use App\Cart\Domain\Model\OrdenItem;
use Doctrine\ORM\EntityManagerInterface;
class DoctrineOrdenItemRepository implements OrdenItemRepository
{
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }
    public function save(OrdenItem $ordenItem): void
    {
        $this->entityManager->persist($ordenItem);
        $this->entityManager->flush();
    }

    public function findById(int $id): ?OrdenItem
    {
        return $this->entityManager->getRepository(OrdenItem::class)->find($id);
    }

    public function delete(OrdenItem $ordenItem): void
    {
        $this->entityManager->remove($ordenItem);
        $this->entityManager->flush();
    }
}