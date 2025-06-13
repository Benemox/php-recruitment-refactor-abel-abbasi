<?php

declare(strict_types=1);

namespace Slot\Domain\Service;

use App\Slot\Domain\Entity\Slot;
use App\Slot\Domain\Service\SlotSynchronizer;
use App\Slot\Domain\ValueObject\SlotId;
use App\Slot\Domain\ValueObject\SlotRange;
use DateTimeImmutable;
use Doctrine\ORM\EntityManagerInterface;
use PHPUnit\Framework\TestCase;
use Psr\Log\LoggerInterface;
use Doctrine\Persistence\ObjectRepository;

final class SlotSynchronizerTest extends TestCase
{
    public function testSyncPersistsNewSlots(): void
    {
        $slotId = SlotId::self('existing-slot-id', new DateTimeImmutable('2025-06-12 10:00'));
        $range = SlotRange::from(new DateTimeImmutable('2025-06-12 10:00'), new DateTimeImmutable('2025-06-12 11:00'));
        $slot = Slot::create($slotId,$range);

        $repository = $this->createMock(ObjectRepository::class);
        $repository->expects($this->once())
            ->method('find')
            ->with($slotId->value())
            ->willReturn(null); // no existe aún

        $entityManager = $this->createMock(EntityManagerInterface::class);
        $entityManager->expects($this->once())
            ->method('getRepository')
            ->willReturn($repository);

        $entityManager->expects($this->once())
            ->method('persist')
            ->with($slot);

        $entityManager->expects($this->once())
            ->method('flush');

        $logger = $this->createMock(LoggerInterface::class);
        $logger->expects($this->once())
            ->method('info')
            ->with($this->stringContains('Persisting new slot'));

        $synchronizer = new SlotSynchronizer($entityManager, $logger);
        $synchronizer->sync([$slot]);

        // ✅ Para evitar "risky test"
        $this->assertTrue(true);
    }

    public function testSyncSkipsExistingSlots(): void
    {
        $slotId = SlotId::self('existing-slot-id', new DateTimeImmutable('2025-06-12 10:00'));
        $range = SlotRange::from(new DateTimeImmutable('2025-06-12 10:00'), new DateTimeImmutable('2025-06-12 11:00'));
        $slot = Slot::create($slotId,$range);

        $repository = $this->createMock(ObjectRepository::class);
        $repository->expects($this->once())
            ->method('find')
            ->with($slotId->value())
            ->willReturn($slot);

        $entityManager = $this->createMock(EntityManagerInterface::class);
        $entityManager->expects($this->once())
            ->method('getRepository')
            ->willReturn($repository);

        $entityManager->expects($this->never())
            ->method('persist');

        $entityManager->expects($this->once())
            ->method('flush');

        $logger = $this->createMock(LoggerInterface::class);
        $logger->expects($this->once())
            ->method('debug')
            ->with($this->stringContains('already exists'));

        $synchronizer = new SlotSynchronizer($entityManager, $logger);
        $synchronizer->sync([$slot]);

        $this->assertTrue(true);
    }
}
