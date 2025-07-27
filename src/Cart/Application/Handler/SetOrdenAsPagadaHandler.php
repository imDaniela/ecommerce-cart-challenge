<?php
namespace App\Cart\Application\Handler;

use App\Cart\Application\Command\SetOrdenAsPagadaCommand;
use App\Cart\Application\Repository\OrdenRepository;
use App\Cart\Application\Assembler\OrdenAssembler;
use App\Cart\Domain\ValueObject\OrdenId;
use Exception;

class SetOrdenAsPagadaHandler
{
    private OrdenRepository $ordenRepository;
    private OrdenAssembler $ordenAssembler;
    public function __construct(OrdenRepository $ordenRepository, OrdenAssembler $ordenAssembler)
    {
        $this->ordenRepository = $ordenRepository;
        $this->ordenAssembler = $ordenAssembler;
    }

    public function __invoke(SetOrdenAsPagadaCommand $command): void
    {
        $orden = $this->ordenRepository->findById(new OrdenId($command->id));
        if (!$orden) {
           throw new Exception("Orden no encontrada");
        }

        $this->ordenRepository->setAsPagada($orden);
    }
}