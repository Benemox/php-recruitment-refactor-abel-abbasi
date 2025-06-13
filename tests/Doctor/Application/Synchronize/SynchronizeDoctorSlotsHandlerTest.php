<?php

declare(strict_types=1);

namespace Doctor\Application\Synchronize;

use App\Doctor\Application\Synchronize\SynchronizeDoctorSlotsCommand;
use App\Doctor\Application\Synchronize\SynchronizeDoctorSlotsHandler;
use App\Doctor\Domain\Entity\Doctor;
use App\Doctor\Domain\Repository\DoctorRepositoryInterface;
use App\Doctor\Domain\Service\DoctorUpdater;
use App\Doctor\Domain\ValueObject\DoctorId;
use App\Doctor\Domain\ValueObject\DoctorName;
use App\Provider\DoctorExternal\Domain\Repository\DoctorExternalProviderInterface;
use App\Slot\Domain\Adapter\SlotAdapter;
use App\Slot\Domain\Entity\Slot;
use App\Slot\Domain\Service\SlotSynchronizer;
use App\Slot\Domain\ValueObject\SlotId;
use App\Slot\Domain\ValueObject\SlotRange;
use PHPUnit\Framework\TestCase;
use Psr\Log\LoggerInterface;

final class SynchronizeDoctorSlotsHandlerTest extends TestCase
{
    public function test_it_synchronizes_doctors_and_slots(): void
    {
        $doctorId = DoctorId::from('1');
        $doctorName = DoctorName::from('Dr. House');
        $doctor = Doctor::create($doctorId, $doctorName);

        $externalProvider = $this->createMock(DoctorExternalProviderInterface::class);
        $externalProvider->method('fetchAllDoctors')
            ->willReturn([['id' => '1', 'name' => 'Dr. House']]);

        $externalProvider->method('fetchSlotsForDoctor')
            ->willReturn([]);

        $doctorRepository = $this->createMock(DoctorRepositoryInterface::class);
        $doctorRepository->method('findById')->willReturn(null);

        $doctorRepository->expects($this->once())
            ->method('save')
            ->with($doctor);

        $doctorUpdater = $this->createMock(DoctorUpdater::class);
        $doctorUpdater->method('create')->willReturn($doctor);
        $doctorUpdater->expects($this->once())->method('update')->with($doctor, $doctorName);
        $doctorUpdater->expects($this->once())->method('clearError')->with($doctor);

        $slotAdapter = $this->createMock(SlotAdapter::class);
        $slotAdapter->method('adapt')->willReturn([]);

        $slotSynchronizer = $this->createMock(SlotSynchronizer::class);
        $slotSynchronizer->expects($this->once())->method('sync')->with([]);

        $logger = $this->createMock(LoggerInterface::class);

        $handler = new SynchronizeDoctorSlotsHandler(
            $externalProvider,
            $doctorRepository,
            $doctorUpdater,
            $slotAdapter,
            $slotSynchronizer,
            $logger
        );

        $handler(new SynchronizeDoctorSlotsCommand());

        $this->assertTrue(true); // para evitar risky test
    }


    public function test_it_marks_doctor_error_on_slot_sync_failure(): void
    {
        $command = $this->createMock(SynchronizeDoctorSlotsCommand::class);

        $doctorId = DoctorId::from('1');
        $doctorName = DoctorName::from('Dr. Strange');
        $rawDoctor = ['id' => 1, 'name' => 'Dr. Strange'];

        $doctor = Doctor::create($doctorId, $doctorName);

        $externalProvider = $this->createMock(DoctorExternalProviderInterface::class);
        $externalProvider->method('fetchAllDoctors')->willReturn([$rawDoctor]);
        $externalProvider->method('fetchSlotsForDoctor')->willThrowException(new \RuntimeException('External API error'));

        $doctorRepository = $this->createMock(DoctorRepositoryInterface::class);
        $doctorRepository->method('findById')->willReturn(null);
        $doctorRepository->expects($this->once())->method('save')->with($this->isInstanceOf(Doctor::class));

        $doctorUpdater = $this->createMock(DoctorUpdater::class);
        $doctorUpdater->method('create')->willReturn($doctor);
        $doctorUpdater->expects($this->once())->method('update')->with($doctor, $doctorName);
        $doctorUpdater->expects($this->once())->method('clearError')->with($doctor);
        $doctorUpdater->expects($this->once())->method('markError')->with($doctor);

        $slotAdapter = $this->createMock(SlotAdapter::class);
        $slotAdapter->expects($this->never())->method('adapt');

        $slotSynchronizer = $this->createMock(SlotSynchronizer::class);
        $slotSynchronizer->expects($this->never())->method('sync');

        $logger = $this->createMock(LoggerInterface::class);
        $logger->expects($this->atLeastOnce())->method('error')
            ->with($this->stringContains('Failed to sync slots for doctor'));

        $handler = new SynchronizeDoctorSlotsHandler(
            $externalProvider,
            $doctorRepository,
            $doctorUpdater,
            $slotAdapter,
            $slotSynchronizer,
            $logger
        );

        $handler->__invoke($command);
    }

}
