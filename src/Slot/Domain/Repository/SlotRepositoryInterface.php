<?php

declare(strict_types=1);

namespace App\Slot\Domain\Repository;

use App\Doctor\Domain\ValueObject\DoctorId;
use App\Slot\Domain\Entity\Slot;
use App\Slot\Domain\ValueObject\SlotId;
use DateTimeImmutable;

interface SlotRepositoryInterface
{
    public function findByDoctorIdAndStart(DoctorId $doctorId, DateTimeImmutable $start): ?Slot;

    public function save(Slot $slot, int $doctorId): void;
}
