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
}
