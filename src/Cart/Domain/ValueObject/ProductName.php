<?php

namespace App\Cart\Domain\ValueObject;

final class ProductName
{
    private string $value;

    public function __construct(string $value)
    {
        if (empty($value)) {
            throw new \InvalidArgumentException('El nombre del producto no puede estar vacío.');
        }
        $this->value = $value;
    }

    public function getValue(): string
    {
        return $this->value;
    }

    public function __toString(): string
    {
        return $this->value;
    }

    public function equals(ProductName $other): bool
    {
        return $this->value === $other->value;
    }
}
