<?php 

namespace App\Cart\Infrastructure\ORM\Type;

use App\Cart\Domain\ValueObject\ProductoId;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\Type;

class ProductIdType extends Type
{
    public function getSQLDeclaration(array $fieldDeclaration, AbstractPlatform $platform)
    {
        return 'INT'; // Define the SQL type for the ProductoId
    }

    public function convertToPHPValue($value, AbstractPlatform $platform)
    {
        return new ProductoId((int) $value);
    }

    public function convertToDatabaseValue($value, AbstractPlatform $platform)
    {
        return $value->getValue();
    }

    public function getName()
    {
        return 'producto_id_vo'; // Custom type name
    }
}