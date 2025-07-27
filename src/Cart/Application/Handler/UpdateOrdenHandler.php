<?php
namespace App\Cart\Application\Handler;

use App\Cart\Application\Command\UpdateOrdenCommand;
use App\Cart\Application\DTO\OrdenDTO;
use App\Cart\Application\Repository\OrdenRepository;
use App\Cart\Application\Assembler\OrdenAssembler;
use App\Cart\Domain\ValueObject\OrdenId;
use App\Cart\Domain\ValueObject\Username;
use Exception;

class UpdateOrdenHandler
{
    private OrdenRepository $ordenRepository;
    private OrdenAssembler $ordenItemAssembler;
    public function __construct(OrdenRepository $ordenItemRepository, OrdenAssembler $ordenAssembler)
    {
        $this->ordenRepository = $ordenItemRepository;
        $this->ordenAssembler = $ordenAssembler;
    }

    public function __invoke(UpdateOrdenCommand $command): ?OrdenDTO
    {
       $orden = $this->ordenRepository->findById(new OrdenId($command->id));
       if (!$orden) {
           throw new Exception("Orden no encontrada");
       }
       $orden->setUsername(new Username($command->username));

       $response = $this->ordenRepository->save($orden);
       $result = $this->ordenAssembler->fromEntity(orden: $response);
       
       return $result;
    }
}