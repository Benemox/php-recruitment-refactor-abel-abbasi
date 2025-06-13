<?php

declare(strict_types=1);

namespace App\Doctor\Domain\Entity;

use App\Doctor\Domain\ValueObject\DoctorId;
use App\Doctor\Domain\ValueObject\DoctorName;

final class Doctor
{
    private bool $error = false;

    private function __construct(
        private DoctorId $id,
        private DoctorName $name,
    ) {}

    public static function create(DoctorId $id, DoctorName $name): self
    {
        return new self($id, $name);
    }

    public function id(): DoctorId
    {
        return $this->id;
    }

    public function name(): DoctorName
    {
        return $this->name;
    }

    public function rename(DoctorName $newName): void
    {
        $this->name = $newName;
    }

    public function markError(): void
    {
        $this->error = true;
    }

    public function clearError(): void
    {
        $this->error = false;
    }

    public function hasError(): bool
    {
        return $this->error;
    }

    public function hasSameName(DoctorName $name): bool
    {
        return $this->name->equals($name);
    }
}
