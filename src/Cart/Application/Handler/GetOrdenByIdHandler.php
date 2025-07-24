<?php
namespace App\Cart\Application\Handler;
use App\Cart\Application\Assembler\OrdenAssembler;
use App\Cart\Application\Query\GetOrdenByIdQuery;
use App\Cart\Application\Repository\OrdenRepository;
use App\Cart\Domain\Model\Orden;
use App\Cart\Domain\ValueObject\OrdenId;

class GetOrdenByIdHandler
{
    private OrdenRepository $ordenRepository;
    private OrdenAssembler $ordenAssembler;

    public function __construct(OrdenRepository $ordenRepository, OrdenAssembler $ordenAssembler)
    {
        $this->ordenRepository = $ordenRepository;
        $this->ordenAssembler = $ordenAssembler;
    }

    public function __invoke(GetOrdenByIdQuery $query): ?Orden
    {
        // Assuming the query returns an Orden object
        $orden = $this->ordenRepository->findById(new OrdenId($query->id));

        if (!$orden) {
            return null; // or throw an exception if you prefer
        }

        return $orden;
    }
}
