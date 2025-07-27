<?php   
namespace App\Cart\Application\Assembler;

use App\Cart\Application\DTO\OrdenItemDTO;
use App\Cart\Domain\Model\OrdenItem;
use App\Cart\Domain\ValueObject\Cantidad;
use App\Cart\Domain\ValueObject\OrdenId;
use App\Cart\Domain\ValueObject\ProductoId;
use App\Cart\Application\Repository\ProductRepository;

class OrdenItemAssembler
{
    private ProductRepository $productRepository;

    public function __construct(ProductRepository $productRepository)
    {
        $this->productRepository = $productRepository;
    }

    public function toDTO(OrdenItem $ordenItem): OrdenItemDTO
    {
        $dto = new OrdenItemDTO();
        $dto->id = $ordenItem->getId();
        $dto->id_producto = $ordenItem->getIdProducto()->getValue();
        $dto->id_orden = $ordenItem->getIdOrden()->getValue();
        $dto->cantidad = $ordenItem->getCantidad()->getValue();

        return $dto;
    }

    public function fromDTO(OrdenItemDTO $dto): OrdenItem
    {
        return new OrdenItem(
            $dto->id,
            new ProductoId($dto->id_producto),
            new OrdenId($dto->id_orden),
            new Cantidad($dto->cantidad)
        );
    }

    public function fromEntity(OrdenItem $ordenItem): OrdenItemDTO
    {
        $idProducto = $ordenItem->getIdProducto()->getValue();
        $producto = $this->productRepository->findById(new ProductoId($idProducto));
        $nombreProducto = $producto ? $producto->getNombre()->getValue() : '';

        $dto = new OrdenItemDTO();
        $dto->id = $ordenItem->getId();
        $dto->id_producto = $idProducto;
        $dto->nombre_producto = $nombreProducto;
        $dto->id_orden = $ordenItem->getIdOrden()->getValue();
        $dto->cantidad = $ordenItem->getCantidad()->getValue();

        return $dto;
    }
}