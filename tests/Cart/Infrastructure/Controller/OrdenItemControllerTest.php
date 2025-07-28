<?php

namespace App\Tests\Cart\Infrastructure\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

final class OrdenItemControllerTest extends WebTestCase
{
    // Tests para crear un item de orden
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

        self::assertResponseStatusCodeSame(201);

        $responseContent = $client->getResponse()->getContent();
        $result = json_decode($responseContent, true)['data'];
        $ordenId = $result['id'] ?? null;

        self::assertEquals('testuser', $result['username']);

        // Ahora creamos un item de orden
        $client->request('POST', 
            '/api/orden/item', 
            [], 
            [], 
            ['CONTENT_TYPE' => 'application/json'], 
            json_encode(['id_orden' => $ordenId, 'id_producto' => 2, 'cantidad' => 3])
        );

        self::assertResponseStatusCodeSame(201);

        $responseContent = $client->getResponse()->getContent();
        $result = json_decode($responseContent, true)['data'];
        
        self::assertEquals($result['id_orden'], $ordenId);
        self::assertEquals($result['id_producto'], 2);
        self::assertEquals($result['cantidad'], 3);
    }
    public function testCreateWithoutFields(): void
    {
        $client = static::createClient();

        // Primero se crea una orden
        $client->request('POST', 
            '/api/orden', 
            [], 
            [], 
            ['CONTENT_TYPE' => 'application/json'], 
            json_encode(['username' => 'testuser'])
        );

        self::assertResponseStatusCodeSame(201);

        $responseContent = $client->getResponse()->getContent();
        $result = json_decode($responseContent, true)['data'];

        self::assertEquals('testuser', $result['username']);

        // Intentamos crear un item de orden sin los campos obligatorios
        $client->request('POST', 
            '/api/orden/item', 
            [], 
            [], 
            ['CONTENT_TYPE' => 'application/json'], 
            json_encode([])
        );

        self::assertResponseStatusCodeSame(400);

        $responseContent = $client->getResponse()->getContent();
        $result = json_decode($responseContent, true);
        
        self::assertEquals('Todos los campos son obligatorios', $result['error']);
    }

    public function testFindByOrdenId(): void
    {
        $client = static::createClient();

        // Primero creamos la orden
        $client->request('POST', 
            '/api/orden', 
            [], 
            [], 
            ['CONTENT_TYPE' => 'application/json'], 
            json_encode(['username' => 'testuser'])
        );

        self::assertResponseStatusCodeSame(201);

        $responseContent = $client->getResponse()->getContent();
        $result = json_decode($responseContent, true)['data'];
        $ordenId = $result['id'] ?? null;

        self::assertEquals('testuser', $result['username']);

        // Ahora se crea un item de la orden recien creada
        $client->request('POST', 
            '/api/orden/item', 
            [], 
            [], 
            ['CONTENT_TYPE' => 'application/json'], 
            json_encode(['id_orden' => $ordenId, 'id_producto' => 1, 'cantidad' => 1])
        );

        self::assertResponseStatusCodeSame(201);

        // Ahora buscamos los items de la orden con el id recien creado
        $client->request('GET', "/api/orden/{$ordenId}/items");

        self::assertResponseStatusCodeSame(200);
        $responseContent = $client->getResponse()->getContent();
        self::assertNotEmpty($responseContent);
    }

    // Tests para actualizar un orden item
    public function testUpdate(): void
    {
        $client = static::createClient();

        // Primero creamos la orden
        $client->request('POST', 
            '/api/orden', 
            [], 
            [], 
            ['CONTENT_TYPE' => 'application/json'], 
            json_encode(['username' => 'testuser'])
        );

        self::assertResponseStatusCodeSame(201);

        $responseContent = $client->getResponse()->getContent();
        $result = json_decode($responseContent, true)['data'];
        $ordenId = $result['id'] ?? null;

        self::assertEquals('testuser', $result['username']);

        // Ahora se crea un item de la orden recien creada
        $client->request('POST', 
            '/api/orden/item', 
            [], 
            [], 
            ['CONTENT_TYPE' => 'application/json'], 
            json_encode(['id_orden' => $ordenId, 'id_producto' => 1, 'cantidad' => 1])
        );

        self::assertResponseStatusCodeSame(201);

        $responseContent = $client->getResponse()->getContent();
        $result = json_decode($responseContent, true)['data'];
        $ordenItemId = $result['id'] ?? null;
        
        self::assertEquals($result['id_orden'], $ordenId);
        self::assertEquals($result['id_producto'], 1);
        self::assertEquals($result['cantidad'], 1);

        $client->request('PUT', 
            "/api/orden/item/{$ordenItemId}", 
            [], 
            [], 
            ['CONTENT_TYPE' => 'application/json'], 
            json_encode(['id_orden' => $ordenId, 'id_producto' => 9, 'cantidad' => 3])
        );

        self::assertResponseStatusCodeSame(200);
    }
    public function testUpdateWithoutId(): void
    {
        $client = static::createClient();

        // Primero se crea una orden
        $client->request('POST', 
            '/api/orden', 
            [], 
            [], 
            ['CONTENT_TYPE' => 'application/json'], 
            json_encode(['username' => 'testuser'])
        );

        self::assertResponseStatusCodeSame(201);

        $responseOrdenContent = $client->getResponse()->getContent();
        $resultOrden = json_decode($responseOrdenContent, true)['data'];
        $ordenId = $resultOrden['id'] ?? null;

        self::assertEquals('testuser', $resultOrden['username']);

        // Ahora se crea un item de la orden recien creada
        $client->request('POST', 
            '/api/orden/item', 
            [], 
            [], 
            ['CONTENT_TYPE' => 'application/json'], 
            json_encode(['id_orden' => $ordenId, 'id_producto' => 1, 'cantidad' => 1])
        );

        self::assertResponseStatusCodeSame(201);

        $responseContent = $client->getResponse()->getContent();
        $result = json_decode($responseContent, true)['data'];
        $ordenItemId = null;
        
        self::assertEquals($result['id_orden'], $ordenId);
        self::assertEquals($result['id_producto'], 1);
        self::assertEquals($result['cantidad'], 1);
        
        // Tarta de actualizar el 
        $client->request('PUT', 
            "/api/orden/item/{$ordenItemId}", 
            [], 
            [], 
            ['CONTENT_TYPE' => 'application/json'], 
            json_encode(['id_orden' => $ordenItemId, 'id_producto' => 3, 'cantidad' => 3])
        );

        // Al enviar id null, se espera un error 404 No Route Found
        // porque la ruta no puede ser resuelta sin un ID válido.
        self::assertResponseStatusCodeSame(404);
    }
    public function testUpdateWithoutFields(): void
    {
        $client = static::createClient();

        // Primero se crea una orden
        $client->request('POST', 
            '/api/orden', 
            [], 
            [], 
            ['CONTENT_TYPE' => 'application/json'], 
            json_encode(['username' => 'testuser'])
        );

        self::assertResponseStatusCodeSame(201);

        $responseOrdenContent = $client->getResponse()->getContent();
        $resultOrden = json_decode($responseOrdenContent, true)['data'];
        $ordenId = $resultOrden['id'] ?? null;

        self::assertEquals('testuser', $resultOrden['username']);

        // Ahora se crea un item de la orden recien creada
        $client->request('POST', 
            '/api/orden/item', 
            [], 
            [], 
            ['CONTENT_TYPE' => 'application/json'], 
            json_encode(['id_orden' => $ordenId, 'id_producto' => 1, 'cantidad' => 1])
        );

        self::assertResponseStatusCodeSame(201);

        $responseContent = $client->getResponse()->getContent();
        $result = json_decode($responseContent, true)['data'];
        $ordenItemId = $result['id'] ?? null;
        
        self::assertEquals($result['id_orden'], $ordenId);
        self::assertEquals($result['id_producto'], 1);
        self::assertEquals($result['cantidad'], 1);
        
        // Tarta de actualizar el 
        $client->request('PUT', 
            "/api/orden/item/{$ordenItemId}", 
            [], 
            [], 
            ['CONTENT_TYPE' => 'application/json'], 
            json_encode(['id_orden' => null, 'id_producto' => null, 'cantidad' => null])
        );

        $responseUpdatedContent = $client->getResponse()->getContent();
        $resultUpdatedContent = json_decode($responseUpdatedContent, true);

        self::assertEquals('Todos los campos son obligatorios', $resultUpdatedContent['error']);
    }
    
    // Tests para eliminar orden item
    public function testDelete(): void
    {
        $client = static::createClient();

        // Primero se crea una orden
        $client->request('POST', 
            '/api/orden', 
            [], 
            [], 
            ['CONTENT_TYPE' => 'application/json'], 
            json_encode(['username' => 'testuser'])
        );

        self::assertResponseStatusCodeSame(201);

        $responseOrdenContent = $client->getResponse()->getContent();
        $resultOrden = json_decode($responseOrdenContent, true)['data'];
        $ordenId = $resultOrden['id'] ?? null;

        self::assertEquals('testuser', $resultOrden['username']);

        // Ahora se crea un item de la orden recien creada
        $client->request('POST', 
            '/api/orden/item', 
            [], 
            [], 
            ['CONTENT_TYPE' => 'application/json'], 
            json_encode(['id_orden' => $ordenId, 'id_producto' => 1, 'cantidad' => 1])
        );

        self::assertResponseStatusCodeSame(201);

        $responseContent = $client->getResponse()->getContent();
        $result = json_decode($responseContent, true)['data'];
        $ordenItemId = $result['id'] ?? null;
        
        self::assertEquals($result['id_orden'], $ordenId);
        self::assertEquals($result['id_producto'], 1);
        self::assertEquals($result['cantidad'], 1);

        $client->request('DELETE', "/api/orden/item/{$ordenItemId}");

        self::assertResponseStatusCodeSame(200);
    }
    public function testDeleteWithoutId(): void
    {
        $client = static::createClient();

        // Primero se crea una orden
        $client->request('POST', 
            '/api/orden', 
            [], 
            [], 
            ['CONTENT_TYPE' => 'application/json'], 
            json_encode(['username' => 'testuser'])
        );

        self::assertResponseStatusCodeSame(201);

        $responseOrdenContent = $client->getResponse()->getContent();
        $resultOrden = json_decode($responseOrdenContent, true)['data'];
        $ordenId = $resultOrden['id'] ?? null;

        self::assertEquals('testuser', $resultOrden['username']);

        // Ahora se crea un item de la orden recien creada
        $client->request('POST', 
            '/api/orden/item', 
            [], 
            [], 
            ['CONTENT_TYPE' => 'application/json'], 
            json_encode(['id_orden' => $ordenId, 'id_producto' => 1, 'cantidad' => 1])
        );

        self::assertResponseStatusCodeSame(201);

        $responseContent = $client->getResponse()->getContent();
        $result = json_decode($responseContent, true)['data'];
        $ordenItemId = null;
        
        self::assertEquals($result['id_orden'], $ordenId);
        self::assertEquals($result['id_producto'], 1);
        self::assertEquals($result['cantidad'], 1);
        
        $client->request('DELETE', "/api/orden/item/{$ordenItemId}");

        // Al enviar id null, se espera un error 404 No Route Found
        // porque la ruta no puede ser resuelta sin un ID válido.
        self::assertResponseStatusCodeSame(404);
    }
}
