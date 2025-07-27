<?php
namespace App\Cart\Application\Handler;
use App\Cart\Application\Assembler\ProductAssembler;
use App\Cart\Application\Query\GetProductsQuery;
use App\Cart\Application\Repository\ProductRepository;

class GetProductsHandler
{
    private ProductRepository $productRepository;
    private ProductAssembler $productAssembler;

    public function __construct(ProductRepository $productRepository, ProductAssembler $productAssembler)
    {
        $this->productRepository = $productRepository;
        $this->productAssembler = $productAssembler;
    }

    public function __invoke(GetProductsQuery $query): array
    {
        // Fetch all products
        $products = $this->productRepository->findAll();

        if (!$products) {
            return []; // or throw an exception if you prefer
        }

        return array_map(fn($item) => $this->productAssembler->fromEntity($item), $products);
    }
}
