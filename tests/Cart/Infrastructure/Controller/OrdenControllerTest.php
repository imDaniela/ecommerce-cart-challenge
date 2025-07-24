<?php

namespace App\Tests\Cart\Infrastructure\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

final class OrdenControllerTest extends WebTestCase
{

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
    }

    public function testShow(): void
    {
        $client = static::createClient();
        $client->request('GET', '/api/orden/3');

        self::assertResponseIsSuccessful();
    }
}
