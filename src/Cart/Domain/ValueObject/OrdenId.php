<?php

namespace App\Cart\Domain\ValueObject;

final class OrdenId
{
    private ?int $value;

    public function __construct(?int $value)
    {
        if ($value < 0) {
            throw new \InvalidArgumentException('El ID de la orden debe ser un número positivo.');
        }
        $this->value = $value;
    }

    public function getValue(): int
    {
        return $this->value;
    }

    public function __toString(): string
    {
        return (string) $this->value;
    }

    public function equals(OrdenId $other): bool
    {
        return $this->value === $other->value;
    }
}
