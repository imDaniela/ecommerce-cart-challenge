<?php
namespace App\Cart\Application\Handler;
use App\Cart\Application\Assembler\OrdenItemAssembler;
use App\Cart\Application\Query\GetOrdenItemByOrdenIdQuery;
use App\Cart\Application\Repository\OrdenItemRepository;
use App\Cart\Domain\Model\OrdenItem;
use App\Cart\Domain\ValueObject\OrdenId;

class GetOrdenItemByOrdenIdHandler
{
    private OrdenItemRepository $ordenItemRepository;
    private OrdenItemAssembler $ordenItemAssembler;

    public function __construct(OrdenItemRepository $ordenItemRepository, OrdenItemAssembler $ordenItemAssembler)
    {
        $this->ordenItemRepository = $ordenItemRepository;
        $this->ordenItemAssembler = $ordenItemAssembler;
    }

    public function __invoke(GetOrdenItemByOrdenIdQuery $query): array
    {
        // Assuming the query returns an array of OrdenItem objects
        $ordenItems = $this->ordenItemRepository->findByOrdenId(new OrdenId($query->id_orden));

        if (empty($ordenItems)) {
            return []; // or throw an exception if you prefer
        }

        return array_map(fn($item) => $this->ordenItemAssembler->fromEntity($item), $ordenItems);
    }
}
