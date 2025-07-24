<?php 

namespace App\Cart\Infrastructure\ORM\Type;

use App\Cart\Domain\ValueObject\Cantidad;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\Type;

class CantidadType extends Type
{
    public function getSQLDeclaration(array $fieldDeclaration, AbstractPlatform $platform)
    {
        return 'INT'; // Define the SQL type for the Cantidad
    }

    public function convertToPHPValue($value, AbstractPlatform $platform)
    {
        return new Cantidad((int) $value);
    }

    public function convertToDatabaseValue($value, AbstractPlatform $platform)
    {
        return $value->getValue();
    }

    public function getName()
    {
        return 'cantidad_vo'; // Custom type name
    }
}