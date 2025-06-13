<?php

declare(strict_types=1);

namespace Slot\Infrastructure\Adapter;

use App\Doctor\Domain\Entity\Doctor;
use App\Doctor\Domain\ValueObject\DoctorId;
use App\Doctor\Domain\ValueObject\DoctorName;
use App\Slot\Domain\Adapter\SlotAdapter;
use App\Slot\Domain\Entity\Slot;
use App\Slot\Domain\ValueObject\SlotId;
use App\Slot\Domain\ValueObject\SlotRange;
use DateTimeImmutable;
use PHPUnit\Framework\TestCase;

final class SlotAdapterTest extends TestCase
{
    public function testAdaptCreatesValidSlotList(): void
    {
        $doctor = Doctor::create(
            DoctorId::from('123'),
            DoctorName::from('Dr. House')
        );

        $rawSlots = [
            [
                'start' => '2025-06-12T10:00:00+00:00',
                'end' => '2025-06-12T10:30:00+00:00',
            ],
            [
                'start' => '2025-06-12T11:00:00+00:00',
                'end' => '2025-06-12T11:30:00+00:00',
            ],
        ];

        $slots = (new SlotAdapter())->adapt($doctor, $rawSlots);

        $this->assertCount(2, $slots);
        foreach ($slots as $slot) {
            $this->assertInstanceOf(Slot::class, $slot);
            $this->assertInstanceOf(SlotId::class, $slot->id());
            $this->assertInstanceOf(SlotRange::class, $slot->range());
            $this->assertInstanceOf(DateTimeImmutable::class, $slot->range()->start);
            $this->assertInstanceOf(DateTimeImmutable::class, $slot->range()->end);
        }
    }
}
