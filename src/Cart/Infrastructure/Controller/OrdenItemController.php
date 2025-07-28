<?php

namespace App\Cart\Infrastructure\Controller;

use App\Cart\Application\Command\DeleteOrdenItemCommand;
use App\Cart\Application\Command\UpdateOrdenItemCommand;
use App\Cart\Application\Query\GetOrdenItemByOrdenIdQuery;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Messenger\MessageBusInterface;
use App\Cart\Application\Command\CreateOrdenItemCommand;
use Symfony\Component\Messenger\Stamp\HandledStamp;
use OpenApi\Attributes as OA;

final class OrdenItemController extends AbstractController
{
    #[Route('/orden/item', name: 'create_orden_item', methods: ['POST'])]
    #[OA\Post(
        summary: 'Crear nuevo item en la orden',
        requestBody: new OA\RequestBody(
            content: new OA\JsonContent(
                properties: [
                    new OA\Property(property: 'id_orden', type: 'integer', description: 'ID de la orden'),
                    new OA\Property(property: 'id_producto', type: 'integer', description: 'ID del producto'),
                    new OA\Property(property: 'cantidad', type: 'integer', description: 'Cantidad del producto')
                ]
            )
        ),
        responses: [
            new OA\Response(
                response: 201,
                description: 'Item creado con éxito',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'success', type: 'string', example: 'Se ha añadido el item a la orden'),
                        new OA\Property(property: 'data', type: 'object', properties: [
                            new OA\Property(property: 'id', type: 'integer', example: 1),
                            new OA\Property(property: 'id_orden', type: 'integer', example: 1),
                            new OA\Property(property: 'id_producto', type: 'integer', example: 1),
                            new OA\Property(property: 'cantidad', type: 'integer', example: 1)
                        ])
                    ]
                )
            ),
            new OA\Response(response: 400, description: 'Error de validación')
        ]
    )]
    public function create(Request $request, MessageBusInterface $bus): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
        $ordenId = $data['id_orden'] ?? null;
        $productId = $data['id_producto'] ?? null;
        $quantity = $data['cantidad'] ?? null;

        if (!$ordenId || !$productId || !$quantity) {
            return new JsonResponse(['error' => 'Todos los campos son obligatorios'], 400);
        }

        $command = new CreateOrdenItemCommand($productId, $ordenId,  $quantity);
        $envelope = $bus->dispatch($command);
        /** @var HandledStamp $handled */
        $handled = $envelope->last(HandledStamp::class);
        $ordenItems = $handled?->getResult();

        return new JsonResponse(['success' => 'Se ha añadido el item a la orden', 'data' => $ordenItems], 201);
    }

    #[Route('/orden/{id_orden}/items', name: 'find_orden_items_by_id_orden', methods: ['GET'])]
    #[OA\Get(
        summary: 'Obtener items de una orden por ID',
        parameters: [
            new OA\Parameter(name: 'id_orden', in: 'path', required: true, description: 'ID de la orden', schema: new OA\Schema(type: 'integer'))
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Items encontrados',
                content: new OA\JsonContent(
                    type: 'array',
                    items: new OA\Items(
                        type: 'object',
                        properties: [
                            new OA\Property(property: 'id', type: 'integer'),
                            new OA\Property(property: 'id_orden', type: 'integer'),
                            new OA\Property(property: 'id_producto', type: 'integer'),
                            new OA\Property(property: 'cantidad', type: 'integer')
                        ]
                    )
                )
            ),
            new OA\Response(response: 404, description: 'Orden vacía')
        ]
    )]
    public function findByOrdenId(int $id_orden, MessageBusInterface $bus): JsonResponse
    {
        $query = new GetOrdenItemByOrdenIdQuery($id_orden);
        $envelope = $bus->dispatch($query);
        /** @var HandledStamp $handled */
        $handled = $envelope->last(HandledStamp::class);
        $ordenItems = $handled?->getResult();

        if (empty($ordenItems)) {
            return new JsonResponse(['error' => 'Orden vacía'], 404);
        }

        return new JsonResponse($ordenItems, 200);
    }

    #[Route('/orden/item/{id}', name: 'update_orden_item', methods: ['PUT'])]
    #[OA\Put(
        summary: 'Actualizar item de una orden',
        parameters: [
            new OA\Parameter(name: 'id', in: 'path', required: true, description: 'ID del item de la orden', schema: new OA\Schema(type: 'integer'))
        ],
        requestBody: new OA\RequestBody(
            content: new OA\JsonContent(
                properties: [
                    new OA\Property(property: 'id_orden', type: 'integer', description: 'ID de la orden'),
                    new OA\Property(property: 'id_producto', type: 'integer', description: 'ID del producto'),
                    new OA\Property(property: 'cantidad', type: 'integer', description: 'Cantidad del producto')
                ]
            )
        ),
        responses: [
            new OA\Response(response: 200, description: 'Item actualizado con éxito'),
            new OA\Response(response: 400, description: 'Error de validación')
        ]
    )]
    public function update(int $id, Request $request, MessageBusInterface $bus): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
        $ordenId = $data['id_orden'] ?? null;
        $productId = $data['id_producto'] ?? null;
        $quantity = $data['cantidad'] ?? null;

        if (!$ordenId || !$productId || !$quantity) {
            return new JsonResponse(['error' => 'Todos los campos son obligatorios'], 400);
        }

        $command = new UpdateOrdenItemCommand($id, $productId, $ordenId,  $quantity);
        $bus->dispatch($command);

        return new JsonResponse(['success' => 'Item actualizado en la orden'], 200);
    }

    #[Route('/orden/item/{id}', name: 'delete_orden_item', methods: ['DELETE'])]
    #[OA\Delete(
        summary: 'Eliminar item de una orden',
        parameters: [
            new OA\Parameter(name: 'id', in: 'path', required: true, description: 'ID del item de la orden', schema: new OA\Schema(type: 'integer'))
        ],
        responses: [
            new OA\Response(response: 200, description: 'Item eliminado con éxito'),
            new OA\Response(response: 404, description: 'Item no encontrado')
        ]
    )]
    public function delete(int $id, MessageBusInterface $bus): JsonResponse
    {
        $command = new DeleteOrdenItemCommand($id);
        $bus->dispatch($command);
        return new JsonResponse(['success' => 'Item eliminado de la orden'], 200);
    }
}