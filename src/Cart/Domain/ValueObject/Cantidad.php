<?php

namespace App\Cart\Domain\ValueObject;

final class Cantidad
{
    private int $value;

    public function __construct(int $value)
    {
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

    public function equals(Cantidad $other): bool
    {
        return $this->value === $other->value;
    }
}
