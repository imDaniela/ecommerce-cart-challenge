<?php
namespace App\Cart\Application\Handler;

use App\Cart\Application\Command\DeleteOrdenItemCommand;
use App\Cart\Application\Repository\OrdenItemRepository;
use App\Cart\Application\Assembler\OrdenItemAssembler;
use Exception;

class DeleteOrdenItemHandler
{
    private OrdenItemRepository $ordenItemRepository;
    private OrdenItemAssembler $ordenItemAssembler;
    public function __construct(OrdenItemRepository $ordenItemRepository, OrdenItemAssembler $ordenItemAssembler)
    {
        $this->ordenItemRepository = $ordenItemRepository;
        $this->ordenItemAssembler = $ordenItemAssembler;
    }

    public function __invoke(DeleteOrdenItemCommand $command): void
    {
       // Assuming the query returns an Orden object
        $ordenItem = $this->ordenItemRepository->findById($command->id);

        if (!$ordenItem) {
            throw new Exception("Item no encontrado");
        }

        $this->ordenItemRepository->delete($ordenItem);
    }
}