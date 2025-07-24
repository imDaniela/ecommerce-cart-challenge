<?php
namespace App\Cart\Application\Repository;

use App\Cart\Domain\Model\OrdenItem;

interface OrdenItemRepository
{
    public function save(OrdenItem $ordenItem): void;

    public function findById(int $id): ?OrdenItem;

    public function delete(OrdenItem $ordenItem): void;
}