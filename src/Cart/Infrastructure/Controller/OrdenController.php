<?php

namespace App\Cart\Infrastructure\Controller;

use App\Cart\Application\Query\GetOrdenByIdQuery;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Messenger\MessageBusInterface;
use App\Cart\Application\Command\CreateOrdenCommand;

final class OrdenController extends AbstractController
{
    
    #[Route('/orden', name: 'create_orden', methods: ['POST'])]
    public function create(Request $request, MessageBusInterface $bus): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
        $username = $data['username'] ?? null;

        if (!$username) {
            return new JsonResponse(['error' => 'Username es obligatorio'], 400);
        }

        $command = new CreateOrdenCommand($username);
        $bus->dispatch($command);

        return new JsonResponse(['success' => 'Orden creada'], 201);
    }

    #[Route('/orden/{id}', name: 'orden_show', methods: ['GET'])]
    public function show($id, MessageBusInterface $bus): JsonResponse
    {
        if (!$id) {
            return new JsonResponse(['error' => 'ID es obligatorio'], 400);
        }

        $query = new GetOrdenByIdQuery($id);
        $orden = $bus->dispatch($query);

        return $this->json(['success' => 'Orden encontrada', 'data' => $orden]);
    }
}
