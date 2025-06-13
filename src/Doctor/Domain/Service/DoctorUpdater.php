<?php

namespace App\Doctor\Domain\Service;

use App\Doctor\Domain\Entity\Doctor;
use App\Doctor\Domain\ValueObject\DoctorId;
use App\Doctor\Domain\ValueObject\DoctorName;

class DoctorUpdater
{
    public function update(Doctor $doctor, DoctorName $newName): void
    {
        if (!$doctor->hasSameName($newName)) {
            $doctor->rename($newName);
        }
    }

    public function clearError(Doctor $doctor): void
    {
        if ($doctor->hasError()) {
            $doctor->clearError();
        }
    }

    public function markError(Doctor $doctor): void
    {
        if (!$doctor->hasError()) {
            $doctor->markError();
        }
    }

    public function create(DoctorId $doctorId, DoctorName $doctorName): Doctor
    {
        return Doctor::create($doctorId, $doctorName);
    }
}
