<?php

namespace App\Cart\Infrastructure\Controller;

use App\Cart\Application\Query\GetProductsQuery;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Messenger\Stamp\HandledStamp;
use Symfony\Component\Routing\Attribute\Route;
use OpenApi\Attributes as OA;

final class ProductController extends AbstractController
{
    #[Route('/products', name: 'list_products', methods: ['GET'])]
    #[OA\Get(
        summary: 'Obtener lista de productos',
        responses: [
            new OA\Response(
                response: 200,
                description: 'Lista de productos',
                content: new OA\JsonContent(
                    type: 'array',
                    items: new OA\Items(
                        type: 'object',
                        properties: [
                            new OA\Property(property: 'id', type: 'integer'),
                            new OA\Property(property: 'nombre', type: 'string'),
                            new OA\Property(property: 'precio', type: 'number', format: 'float'),
                            new OA\Property(property: 'descripcion', type: 'string')
                        ]
                    )
                )
            ),
            new OA\Response(response: 404, description: 'No se encontraron productos')
        ]
    )]
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
