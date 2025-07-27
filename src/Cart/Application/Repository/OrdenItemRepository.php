<?php
namespace App\Cart\Application\Repository;

use App\Cart\Domain\Model\OrdenItem;
use App\Cart\Domain\ValueObject\OrdenId;

interface OrdenItemRepository
{
    public function save(OrdenItem $ordenItem): void;

    public function findById(int $id): ?OrdenItem;

    public function findByOrdenId(OrdenId $ordenId): array; // OrdenItem[]

    public function delete(OrdenItem $ordenItem): void;
}