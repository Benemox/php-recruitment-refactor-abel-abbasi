<?php

declare(strict_types=1);

namespace App\Slot\Domain\Adapter;

use App\Doctor\Domain\Entity\Doctor;
use App\Slot\Domain\Entity\Slot;
use App\Slot\Domain\ValueObject\SlotId;
use App\Slot\Domain\ValueObject\SlotRange;
use DateTime;
use InvalidArgumentException;

readonly class SlotAdapter
{
    /**
     * @param Doctor $doctor
     * @param array $rawSlots
     * @return Slot[]
     */
    public function adapt(Doctor $doctor, array $rawSlots): array
    {
        $slots = [];

        foreach ($rawSlots as $index => $rawSlot) {
            try {
                if (!isset($rawSlot['start'], $rawSlot['end'])) {
                    throw new InvalidArgumentException("Slot at index $index is missing 'start' or 'end'");
                }

                $start = new \DateTimeImmutable($rawSlot['start']);
                $end = new \DateTimeImmutable($rawSlot['end']);

                if ($start >= $end) {
                    throw new InvalidArgumentException("Slot start must be before end at index $index");
                }

                $range = SlotRange::from($start, $end);
                $slots[] = Slot::create(SlotId::self($doctor->id()->value(),$start), $range);
            } catch (\Throwable $e) {
                // Optionally log the error or add a mechanism to report invalid slots
                // For now we skip invalid slots silently
                continue;
            }
        }

        return $slots;
    }
}
