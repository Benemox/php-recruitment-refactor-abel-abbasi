<?php

declare(strict_types=1);

namespace App\Doctor\Domain\Repository;

use App\Doctor\Domain\Entity\Doctor;
use App\Doctor\Domain\ValueObject\DoctorId;

interface DoctorRepositoryInterface
{
    public function findById(DoctorId $id): ?Doctor;

    public function save(Doctor $doctor): void;

    public function remove(Doctor $doctor): void;
}
