<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Cart\Domain\Model\Product;
use App\Cart\Domain\ValueObject\ProductName;
use App\Cart\Domain\ValueObject\ProductPrice;

class ProductFixture extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $products = [
            ['Zapatillas de ciclismo', 49.99],
            ['Mochila Hidratación', 20.30],
            ['Casco de ciclismo', 35.00],
            ['Gafas de sol deportivas', 15.50],
            ['Camiseta transpirable', 10.00],
            ['Pantalones cortos de ciclismo', 18.00],
            ['Calcetines técnicos', 8.99],
            ['Kit de mancuernas', 95.65],
            ['Guantes de verano', 9.99],
        ];

        foreach ($products as [$nombre, $precio]) {
            $product = new Product();
            $product->setNombre(new ProductName($nombre));
            $product->setPrecio(new ProductPrice($precio));
            $manager->persist($product);
        }

        $manager->flush();
    }
}
