<?php

namespace App\Cart\Domain\ValueObject;

final class ProductPrice
{
    private float $value;

    public function __construct(float $value)
    {
        if ($value < 0) {
            throw new \InvalidArgumentException('El precio del producto debe ser un número positivo.');
        }
        $this->value = $value;
    }

    public function getValue(): float
    {
        return $this->value;
    }

    public function __toString(): string
    {
        return (string) $this->value;
    }

    public function equals(ProductPrice $other): bool
    {
        return $this->value === $other->value;
    }
}
