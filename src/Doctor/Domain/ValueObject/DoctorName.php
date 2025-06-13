<?php

declare(strict_types=1);

namespace App\Doctor\Domain\ValueObject;

use App\Doctor\Domain\Exception\InvalidDoctorNameException;

final readonly class DoctorName
{
    private function __construct(private string $value) {}

    public static function from(string $name): self
    {
        if ('' === trim($name)) {
            throw InvalidDoctorNameException::empty();
        }

        if (mb_strlen($name) < 3) {
            throw InvalidDoctorNameException::tooShort();
        }

        $normalized = ucwords(strtolower(trim($name)));
        return new self($normalized);
    }

    public function value(): string
    {
        return $this->value;
    }

    public function equals(DoctorName $name): bool
    {
        return $this->value === $name->value();
    }
}

