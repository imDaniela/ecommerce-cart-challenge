<?php

namespace App\Cart\Domain\ValueObject;

final class Username
{
    private string $value;

    public function __construct(string $value)
    {
        $this->value = $value;
    }

    public function __set(string $name, string $value): void
    {
        if (empty($value)) {
            throw new \InvalidArgumentException('El nombre de usuario no puede estar vacío.');
        }
        $this->value = $value;
    }
    public function getValue(): string
    {
        return $this->value;
    }
    public function setValue(string $value): void
    {
        $this->value = $value;
    }

    public function __toString(): string
    {
        return $this->value;
    }

    public function equals(Username $other): bool
    {
        return $this->value === $other->value;
    }
}
