<?php 

namespace App\Cart\Infrastructure\ORM\Type;

use App\Cart\Domain\ValueObject\ProductName;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\Type;

class ProductNameType extends Type
{
    public function getSQLDeclaration(array $fieldDeclaration, AbstractPlatform $platform)
    {
        return 'VARCHAR(100)'; // Define the SQL type for the ProductName
    }

    public function convertToPHPValue($value, AbstractPlatform $platform)
    {
        return new ProductName((string) $value);
    }

    public function convertToDatabaseValue($value, AbstractPlatform $platform)
    {
        return $value->getValue();
    }

    public function getName()
    {
        return 'product_name_vo'; // Custom type name
    }
}