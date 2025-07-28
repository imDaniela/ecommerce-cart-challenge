<?php

namespace App\Cart\Infrastructure\Controller;

use App\Cart\Application\Command\SetOrdenAsPagadaCommand;
use App\Cart\Application\Command\UpdateOrdenCommand;
use App\Cart\Application\Query\GetOrdenByIdQuery;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Messenger\MessageBusInterface;
use App\Cart\Application\Command\CreateOrdenCommand;
use Symfony\Component\Messenger\Stamp\HandledStamp;
use OpenApi\Attributes as OA;

final class OrdenController extends AbstractController
{
    
    #[Route('/orden', name: 'create_orden', methods: ['POST'])]
    #[OA\Post(
        summary: 'Crear nueva orden',
        requestBody: new OA\RequestBody(
            content: new OA\JsonContent(
                properties: [
                    new OA\Property(
                        property: 'username',
                        type: 'string',
                        description: 'El nombre del comprador'
                    )
                ]
            )
        ),
        responses: [
            new OA\Response(
                response: 201,
                description: 'Orden creada con éxito',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(
                            property: 'success',
                            type: 'string',
                            example: 'Orden creada con éxito'
                        ),
                        new OA\Property(
                            property: 'data',
                            type: 'object',
                            properties: [
                                new OA\Property(
                                    property: 'id',
                                    type: 'integer',
                                    example: 1
                                ),
                                new OA\Property(
                                    property: 'username',
                                    type: 'string',
                                    example: 'John Doe'
                                )
                            ]
                        )
                    ]
                )
            )
        ]
    )]
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
    #[OA\Get(
        summary: 'Obtener detalles de una orden',
        parameters: [
            new OA\Parameter(
                name: 'id',
                in: 'path',
                description: 'ID de la orden',
                required: true,
                schema: new OA\Schema(type: 'integer')
            )
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Orden encontrada',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(
                            property: 'success',
                            type: 'string',
                            example: 'Orden encontrada'
                        ),
                        new OA\Property(
                            property: 'data',
                            type: 'object',
                            properties: [
                                new OA\Property(
                                    property: 'id',
                                    type: 'integer',
                                    example: 1
                                ),
                                new OA\Property(
                                    property: 'username',
                                    type: 'string',
                                    example: 'John Doe'
                                )
                            ]
                        )
                    ]
                )
            )
        ]
    )]
    public function show($id, MessageBusInterface $bus): JsonResponse
    {
        if (!$id) {
            return new JsonResponse(['error' => 'ID es obligatorio'], 400);
        }

        $query = new GetOrdenByIdQuery($id);
        $envelope = $bus->dispatch($query);
        /** @var HandledStamp $handled */
        $handled = $envelope->last(HandledStamp::class);
        $orden = $handled?->getResult();

        return $this->json(['success' => 'Orden encontrada', 'data' => $orden]);
    }

    #[Route('/orden/{id}', name: 'update_orden', methods: ['PUT'])]
    #[OA\Put(
        summary: 'Actualizar una orden',
        parameters: [
            new OA\Parameter(
                name: 'id',
                in: 'path',
                description: 'ID de la orden a actualizar',
                required: true,
                schema: new OA\Schema(type: 'integer')
            )
        ],
        requestBody: new OA\RequestBody(
            content: new OA\JsonContent(
                properties: [
                    new OA\Property(
                        property: 'username',
                        type: 'string',
                        description: 'Nuevo nombre del comprador'
                    )
                ]
            )
        ),
        responses: [
            new OA\Response(
                response: 200,
                description: 'Orden actualizada con éxito',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(
                            property: 'success',
                            type: 'string',
                            example: 'Orden actualizada con éxito'
                        ),
                        new OA\Property(
                            property: 'data',
                            type: 'object',
                            properties: [
                                new OA\Property(
                                    property: 'id',
                                    type: 'integer',
                                    example: 1
                                ),
                                new OA\Property(
                                    property: 'username',
                                    type: 'string',
                                    example: 'John Doe'
                                )
                            ]
                        )
                    ]
                )
            )
        ]
    )]
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
    #[OA\Get(
        summary: 'Marcar una orden como pagada',
        parameters: [
            new OA\Parameter(
                name: 'id',
                in: 'path',
                description: 'ID de la orden a marcar como pagada',
                required: true,
                schema: new OA\Schema(type: 'integer')
            )
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Orden marcada como pagada',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(
                            property: 'success',
                            type: 'string',
                            example: 'Orden marcada como pagada'
                        )
                    ]
                )
            )
        ]
    )]
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
