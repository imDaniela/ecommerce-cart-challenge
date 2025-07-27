<?php

namespace App\Cart\Infrastructure\Controller;

use App\Cart\Application\Query\GetProductsQuery;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Messenger\Stamp\HandledStamp;
use Symfony\Component\Routing\Attribute\Route;

final class ProductController extends AbstractController
{
    #[Route('/products', name: 'list_products', methods: ['GET'])]
    public function list(MessageBusInterface $bus): JsonResponse
    {
        $query = new GetProductsQuery();
        $envelope = $bus->dispatch($query);

        /** @var HandledStamp $handled */
        $handled = $envelope->last(HandledStamp::class);
        $products = $handled?->getResult();

        if (empty($products)) {
            return new JsonResponse(['error' => 'No products found'], 404);
        }

        return new JsonResponse($products);
    }
}
