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

final class OrdenItemController extends AbstractController
{
    #[Route('/orden/item', name: 'create_orden_item', methods: ['POST'])]
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
        $bus->dispatch($command);

        return new JsonResponse(['success' => 'Se ha añadido el item a la orden'], 201);
    }

    #[Route('/orden/{id_orden}/items', name: 'find_orden_items_by_id_orden', methods: ['GET'])]
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
    public function delete(int $id, MessageBusInterface $bus): JsonResponse
    {
        $command = new DeleteOrdenItemCommand($id);
        $bus->dispatch($command);
        return new JsonResponse(['success' => 'Item eliminado de la orden'], 200);
    }
}