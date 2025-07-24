<?php

namespace App\Tests\Cart\Infrastructure\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

final class OrdenItemControllerTest extends WebTestCase
{
    public function testCreate(): void
    {
        $client = static::createClient();
        $client->request('POST', 
            '/api/orden/item', 
            [], 
            [], 
            ['CONTENT_TYPE' => 'application/json'], 
            json_encode(['id_orden' => 1, 'id_producto' => 2, 'cantidad' => 3])
        );

        self::assertResponseStatusCodeSame(201);
    }

    public function testUpdate(): void
    {
        $client = static::createClient();
        $client->request('PUT', 
            '/api/orden/item/2', 
            [], 
            [], 
            ['CONTENT_TYPE' => 'application/json'], 
            json_encode(['id_orden' => 1, 'id_producto' => 9, 'cantidad' => 3])
        );

        self::assertResponseStatusCodeSame(200);
    }

    public function testDelete(): void
    {
        $client = static::createClient();
        $client->request('DELETE', '/api/orden/item/6');

        self::assertResponseStatusCodeSame(200);
    }
}
