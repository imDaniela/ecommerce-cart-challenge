<?php 

namespace App\Cart\Infrastructure\ORM\Type;

use App\Cart\Domain\ValueObject\ProductPrice;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\Type;

class ProductPriceType extends Type
{
    public function getSQLDeclaration(array $fieldDeclaration, AbstractPlatform $platform)
    {
        return 'FLOAT(10,2)'; // Define the SQL type for the ProductPrice
    }

    public function convertToPHPValue($value, AbstractPlatform $platform)
    {
        return new ProductPrice((float) $value);
    }

    public function convertToDatabaseValue($value, AbstractPlatform $platform)
    {
        return $value->getValue();
    }

    public function getName()
    {
        return 'product_price_vo'; // Custom type name
    }
}