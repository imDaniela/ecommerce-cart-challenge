<?php   
namespace App\Cart\Application\Assembler;
use App\Cart\Application\DTO\ProductDTO;
use App\Cart\Domain\Model\Product;
use App\Cart\Domain\ValueObject\ProductName;
use App\Cart\Domain\ValueObject\ProductoId;
use App\Cart\Domain\ValueObject\ProductPrice;

class ProductAssembler
{
    public function toDTO(Product $product): ProductDTO
    {
        $dto = new ProductDTO();
        $dto->id = $product->getId();
        $dto->nombre = $product->getNombre();
        $dto->precio = $product->getPrecio();

        return $dto;
    }

    public function fromDTO(ProductDTO $dto): Product
    {
        return new Product(
            new ProductoId($dto->id),
            new ProductName($dto->nombre),
            new ProductPrice($dto->precio)
        );
    }
}