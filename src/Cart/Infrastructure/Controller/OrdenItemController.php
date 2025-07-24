<?php

namespace App\Cart\Infrastructure\Controller;

use App\Cart\Application\Command\DeleteOrdenItemCommand;
use App\Cart\Application\Command\UpdateOrdenItemCommand;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Messenger\MessageBusInterface;
use App\Cart\Application\Command\CreateOrdenItemCommand;

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