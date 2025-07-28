<?php

namespace App\Tests\Cart\Infrastructure\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

final class ProductControllerTest extends WebTestCase
{
    public function testList(): void
    {
        $client = static::createClient();
        $client->request('GET', '/api/products');

        $responseContent = $client->getResponse()->getContent();

        self::assertNotEmpty($responseContent);
        self::assertResponseIsSuccessful();
    }
}
