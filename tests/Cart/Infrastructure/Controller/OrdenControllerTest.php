<?php

namespace App\Tests\Cart\Infrastructure\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

final class OrdenControllerTest extends WebTestCase
{
    // Tests para crear una orden
    public function testCreate(): void
    {
        $client = static::createClient();
        $client->request('POST', 
            '/api/orden', 
            [], 
            [], 
            ['CONTENT_TYPE' => 'application/json'], 
            json_encode(['username' => 'testuser'])
        );

        $responseContent = $client->getResponse()->getContent();
        $result = json_decode($responseContent, true)['data'];

        self::assertEquals('testuser', $result['username']);
    }
    public function testCreateWithoutUsername(): void
    {
        $client = static::createClient();
        $client->request('POST', 
            '/api/orden', 
            [], 
            [], 
            ['CONTENT_TYPE' => 'application/json'], 
            json_encode([])
        );

        self::assertResponseStatusCodeSame(400);

        $responseContent = $client->getResponse()->getContent();
        $result = json_decode($responseContent, true);
        
        self::assertEquals('Username es obligatorio', $result['error']);
    }

    // Tests para actualizar una orden
    public function testUpdate(): void
    {
        $client = static::createClient();

        // Crear una orden para poder actualizarla
        $client->request('POST', 
            '/api/orden', 
            [], 
            [], 
            ['CONTENT_TYPE' => 'application/json'], 
            json_encode(['username' => 'testuser'])
        );

        self::assertResponseStatusCodeSame(201);

        // Obtener el ID de la orden creada
        $responseContent = $client->getResponse()->getContent();
        $result = json_decode($responseContent, true)['data'];
        $ordenId = $result['id'] ?? null;

        $this->assertNotNull($ordenId, 'No se recibió un ID de orden');

        // Ahora hacer la solicitud PUT para actualizar la orden
        $client->request('PUT', "/api/orden/{$ordenId}", 
            [], 
            [], 
            ['CONTENT_TYPE' => 'application/json'], 
            json_encode(['username' => 'updateduser'])
        );

        self::assertResponseStatusCodeSame(200);

        $responseContent = $client->getResponse()->getContent();
        $resultUpdate = json_decode($responseContent, true)['data'];
        self::assertEquals('updateduser', $resultUpdate['username']);
    }
    public function testUpdateWithoutId(): void
    {
        $ordenId = null;
        $client = static::createClient();
        $client->request('PUT', 
            "/api/orden/{$ordenId}", 
            [], 
            [], 
            ['CONTENT_TYPE' => 'application/json'],
            json_encode(['username' => 'testuser'])
        );

        // Al enviar id null, se espera un error 404 No Route Found
        // porque la ruta no puede ser resuelta sin un ID válido.
        self::assertResponseStatusCodeSame(404);
    }
    public function testUpdateWithoutUsername(): void
    {
        $client = static::createClient();
        $client->request('PUT', 
            '/api/orden/1', 
            [], 
            [], 
            ['CONTENT_TYPE' => 'application/json'], 
            json_encode(["username" => null])
        );

        self::assertResponseStatusCodeSame(400);

        $responseContent = $client->getResponse()->getContent();
        $result = json_decode($responseContent, true);
        
        self::assertEquals('Username es obligatorio', $result['error']);
    }

    // Tests para mostrar una orden
    public function testShow(): void
    {
        $client = static::createClient();

        // Crear una orden para poder mostrarla
        $client->request('POST', 
            '/api/orden', 
            [], 
            [], 
            ['CONTENT_TYPE' => 'application/json'], 
            json_encode(['username' => 'testusershow'])
        );

        self::assertResponseStatusCodeSame(201);

        // Obtener el ID de la orden creada
        $responseContent = $client->getResponse()->getContent();
        $result = json_decode($responseContent, true)['data'];
        $ordenId = $result['id'] ?? null;

        $this->assertNotNull($ordenId, 'No se recibió un ID de orden');

        // Ahora hacer la solicitud GET para mostrar la orden
        $client->request('GET', "/api/orden/{$ordenId}");

        self::assertEquals('testusershow', $result['username']);
    }

    // Tests para marcar una orden como pagada
    public function testSetOrdenAsPagada(): void
    {
        $client = static::createClient();
        
        // Crear una orden para poder marcar como pagada
        $client->request('POST', 
            '/api/orden', 
            [], 
            [], 
            ['CONTENT_TYPE' => 'application/json'], 
            json_encode(['username' => 'testusersetpagada'])
        );

        self::assertResponseStatusCodeSame(201);

        // Obtener el ID de la orden creada
        $responseContent = $client->getResponse()->getContent();
        $result = json_decode($responseContent, true)['data'];
        $ordenId = $result['id'] ?? null;

        $this->assertNotNull($ordenId, 'No se recibió un ID de orden');

        // Ahora hacer la solicitud POST para marcar la orden como pagada
        $client->request('POST', "/api/orden/{$ordenId}/checkout", 
            [], 
            [], 
            ['CONTENT_TYPE' => 'application/json'], 
        );

        self::assertResponseStatusCodeSame(200);

        $responseContent = $client->getResponse()->getContent();
        $resultCheckout = json_decode($responseContent, true)['success'];
        self::assertEquals('Orden marcada como pagada', $resultCheckout);
    }
    public function testSetOrdenAsPagadaWithoutId(): void
    {
        $ordenId = null;
        $client = static::createClient();
        $client->request('POST', 
            "/api/orden/{$ordenId}/checkout", 
            [], 
            [], 
            ['CONTENT_TYPE' => 'application/json']
        );

        // Al enviar id null, se espera un error 404 No Route Found
        // porque la ruta no puede ser resuelta sin un ID válido.
        self::assertResponseStatusCodeSame(404);
    }
}
