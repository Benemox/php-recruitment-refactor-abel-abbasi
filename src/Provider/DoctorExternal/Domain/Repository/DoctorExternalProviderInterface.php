<?php

namespace App\Provider\DoctorExternal\Domain\Repository;

use App\Doctor\Domain\ValueObject\DoctorId;

interface DoctorExternalProviderInterface
{
    /**
     * @return array<array{id: int, name: string}>
     */
    public function fetchAllDoctors(): array;

    /**
     * @param int|string $doctorId
     * @return array<array{start: string, end: string}>
     */
    public function fetchSlotsForDoctor(int|string $doctorId): array;
}
