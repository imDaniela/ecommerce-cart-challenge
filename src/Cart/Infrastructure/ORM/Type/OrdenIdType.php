<?php 

namespace App\Cart\Infrastructure\ORM\Type;

use App\Cart\Domain\ValueObject\OrdenId;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\Type;

class OrdenIdType extends Type
{
    public function getSQLDeclaration(array $fieldDeclaration, AbstractPlatform $platform)
    {
        return 'INT'; // Define the SQL type for the OrdenId
    }

    public function convertToPHPValue($value, AbstractPlatform $platform)
    {
        return new OrdenId((int) $value);
    }

    public function convertToDatabaseValue($value, AbstractPlatform $platform)
    {
        return $value->getValue();
    }

    public function getName()
    {
        return 'orden_id_vo'; // Custom type name
    }
}