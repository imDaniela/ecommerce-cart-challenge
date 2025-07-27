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
    public function save(Orden $orden): ?Orden
    {
        $this->entityManager->persist($orden);
        $this->entityManager->flush();

        return $orden;
    }
    public function findById(OrdenId $id): ?Orden
    {
        return $this->entityManager->getRepository(Orden::class)->find($id);
    }

    public function setAsPagada(Orden $orden): void
    {
        $orden->setPagado(true);

        // TODO: Implementar lógica para manejar el pago de la orden
        $this->save($orden);
    }
}