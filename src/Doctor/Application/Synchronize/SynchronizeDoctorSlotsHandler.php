<?php

declare(strict_types=1);

namespace App\Doctor\Application\Synchronize;

use App\Doctor\Domain\Repository\DoctorRepositoryInterface;
use App\Doctor\Domain\Service\DoctorUpdater;
use App\Doctor\Domain\ValueObject\DoctorId;
use App\Doctor\Domain\ValueObject\DoctorName;
use App\Provider\DoctorExternal\Domain\Repository\DoctorExternalProviderInterface;
use App\Slot\Domain\Adapter\SlotAdapter;
use App\Slot\Domain\Service\SlotSynchronizer;
use Psr\Log\LoggerInterface;

final readonly class SynchronizeDoctorSlotsHandler
{
    public function __construct(
        private DoctorExternalProviderInterface $externalProvider,
        private DoctorRepositoryInterface       $doctorRepository,
        private DoctorUpdater                   $doctorUpdater,
        private SlotAdapter                     $slotAdapter,
        private SlotSynchronizer                $slotSynchronizer,
        private LoggerInterface                 $logger,
    ) {}

    public function __invoke(SynchronizeDoctorSlotsCommand $command): void
    {
        $this->logger->info('Starting doctor slots synchronization...');

        $rawDoctors = $this->externalProvider->fetchAllDoctors();

        foreach ($rawDoctors as $rawDoctor) {
            $doctorId = DoctorId::from((string) $rawDoctor['id']);
            $doctorName = DoctorName::from($rawDoctor['name']);

            $doctor = $this->doctorRepository->findById($doctorId)
                ?? $this->doctorUpdater->create($doctorId, $doctorName);

            $this->doctorUpdater->update($doctor, $doctorName);
            $this->doctorUpdater->clearError($doctor);

            try {
                $rawSlots = $this->externalProvider->fetchSlotsForDoctor($rawDoctor['id']);
                $slots = $this->slotAdapter->adapt($doctor, $rawSlots);

                $this->slotSynchronizer->sync($slots);
                $this->logger->info(sprintf('Synchronized slots for doctor %s', $doctorId->value()));
            } catch (\Throwable $e) {
                $this->doctorUpdater->markError($doctor);
                $this->logger->error(sprintf('Failed to sync slots for doctor %s: %s', $doctorId->value(), $e->getMessage()));
            }

            $this->doctorRepository->save($doctor);
        }

        $this->logger->info('Doctor slots synchronization finished.');
    }
}
