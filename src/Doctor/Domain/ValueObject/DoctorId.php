<?php

declare(strict_types=1);

namespace App\Doctor\Domain\ValueObject;

final readonly class DoctorId
{
    private function __construct(private string $value) {}

    public static function from(string|int $raw): self
    {

        $value = (string) $raw;

        if ($value === '') {
            throw new \InvalidArgumentException('DoctorId cannot be empty');
        }

        return new self($value);
    }

    public function value(): string
    {
        return $this->value;
    }

    public function equals(DoctorId $other): bool
    {
        return $this->value === $other->value;
    }
}
