<?php
namespace App\Cart\Application\Handler;

use App\Cart\Application\Command\UpdateOrdenItemCommand;
use App\Cart\Application\Repository\OrdenItemRepository;
use App\Cart\Application\Assembler\OrdenItemAssembler;
use App\Cart\Domain\ValueObject\Cantidad;
use App\Cart\Domain\ValueObject\OrdenId;
use App\Cart\Domain\ValueObject\ProductoId;
use Exception;

class UpdateOrdenItemHandler
{
    private OrdenItemRepository $ordenItemRepository;
    private OrdenItemAssembler $ordenItemAssembler;
    public function __construct(OrdenItemRepository $ordenItemRepository, OrdenItemAssembler $ordenItemAssembler)
    {
        $this->ordenItemRepository = $ordenItemRepository;
        $this->ordenItemAssembler = $ordenItemAssembler;
    }

    public function __invoke(UpdateOrdenItemCommand $command): void
    {
       $ordenItem = $this->ordenItemRepository->findById($command->id);
       if (!$ordenItem) {
           throw new Exception("Item no encontrado");
       }
       $ordenItem->setIdProducto(new ProductoId($command->id_producto));
       $ordenItem->setIdOrden(new OrdenId($command->id_orden));
       $ordenItem->setCantidad(new Cantidad($command->cantidad));

       $this->ordenItemRepository->save($ordenItem);
    }
}