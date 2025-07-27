<?php   
namespace App\Cart\Application\Assembler;
use App\Cart\Application\DTO\OrdenDTO;
use App\Cart\Domain\Model\Orden;
use App\Cart\Domain\ValueObject\Username;

class OrdenAssembler
{
    public function toDTO(Orden $orden): OrdenDTO
    {
        $dto = new OrdenDTO();
        $dto->id = $orden->getId();
        $dto->username = (string) $orden->getUsername();
        $dto->pagado = (string) $orden->getPagado();

        return $dto;
    }

    public function fromDTO(OrdenDTO $dto): Orden
    {
        return new Orden(
            $dto->id,
            new Username($dto->username),
            (bool) $dto->pagado
        );
    }

    public function fromEntity(Orden $orden): OrdenDTO
    {
        $dto = new OrdenDTO();
        $dto->id = $orden->getId()->getValue();
        $dto->username = $orden->getUsername()->getValue();
        $dto->pagado = $orden->getPagado();

        return $dto;
    }

}