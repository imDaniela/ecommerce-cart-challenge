<?php

namespace App\Cart\Infrastructure\Controller;

use App\Cart\Application\Command\SetOrdenAsPagadaCommand;
use App\Cart\Application\Command\UpdateOrdenCommand;
use App\Cart\Application\Query\GetOrdenByIdQuery;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Messenger\MessageBusInterface;
use App\Cart\Application\Command\CreateOrdenCommand;
use Symfony\Component\Messenger\Stamp\HandledStamp;

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
        $envelope = $bus->dispatch($command);
        /** @var HandledStamp $handled */
        $handled = $envelope->last(HandledStamp::class);
        $orden = $handled?->getResult();
        return new JsonResponse(['success' => 'Orden creada', 'data' => $orden], 201);
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

    #[Route('/orden/{id}', name: 'update_orden', methods: ['PUT'])]
    public function update($id, Request $request, MessageBusInterface $bus): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
        $username = $data['username'];

        if (!$id) {
            return new JsonResponse(['error' => 'ID es obligatorio'], 400);
        }

        if (!$username) {
            return new JsonResponse(['error' => 'Username es obligatorio'], 400);
        }
        
        $command = new UpdateOrdenCommand($id, $username);
        $envelope = $bus->dispatch($command);
        /** @var HandledStamp $handled */
        $handled = $envelope->last(HandledStamp::class);
        $orden = $handled?->getResult();

        return new JsonResponse(['success' => 'Orden actualizada con éxito', 'data' => $orden], 200);
    }

    #[Route('/orden/{id}/checkout', name: 'set_orden_as_pagada', methods: ['GET'])]
    public function setOrdenAsPagada($id, MessageBusInterface $bus): JsonResponse
    {
        if (!$id) {
            return new JsonResponse(['error' => 'ID es obligatorio'], 400);
        }


        $command = new SetOrdenAsPagadaCommand($id);
        $bus->dispatch($command);

        return new JsonResponse(['success' => 'Orden marcada como pagada'], 200);
    }
}
