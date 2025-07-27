<?php
namespace App\Cart\Application\Handler;
use App\Cart\Application\Assembler\ProductAssembler;
use App\Cart\Application\Query\GetProductByIdQuery;
use App\Cart\Application\Repository\ProductRepository;
use App\Cart\Domain\Model\Product;
use App\Cart\Domain\ValueObject\ProductoId;

class GetProductByIdHandler
{
    private ProductRepository $productRepository;
    private ProductAssembler $productAssembler;

    public function __construct(ProductRepository $productRepository, ProductAssembler $productAssembler)
    {
        $this->productRepository = $productRepository;
        $this->productAssembler = $productAssembler;
    }

    public function __invoke(GetProductByIdQuery $query): ?Product
    {
        // Assuming the query returns a Product object
        $product = $this->productRepository->findById(new ProductoId($query->id));

        if (!$product) {
            return null; // or throw an exception if you prefer
        }

        return $product;
    }
}
