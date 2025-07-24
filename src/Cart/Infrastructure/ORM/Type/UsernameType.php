<?php 

namespace App\Cart\Infrastructure\ORM\Type;

use App\Cart\Domain\ValueObject\Username;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\Type;

class UsernameType extends Type
{
    public function getSQLDeclaration(array $fieldDeclaration, AbstractPlatform $platform)
    {
        return 'VARCHAR(100)'; // Define the SQL type for the username
    }

    public function convertToPHPValue($value, AbstractPlatform $platform)
    {
        return new Username((string) $value);
    }

    public function convertToDatabaseValue($value, AbstractPlatform $platform)
    {
        return $value->getValue();
    }

    public function getName()
    {
        return 'username_vo'; // Custom type name
    }
}