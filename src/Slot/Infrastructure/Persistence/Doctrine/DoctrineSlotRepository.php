<?php

declare(strict_types=1);

namespace App\Slot\Infrastructure\Persistence\Doctrine;

use App\Doctor\Domain\ValueObject\DoctorId;
use App\Slot\Domain\Entity\Slot;
use App\Slot\Domain\Repository\SlotRepositoryInterface;
use App\Slot\Infrastructure\Adapter\SlotAdapter;
use DateTimeImmutable;
use Doctrine\ORM\EntityManagerInterface;

final readonly class DoctrineSlotRepository implements SlotRepositoryInterface
{
    public function __construct(
        private EntityManagerInterface $entityManager
    )
    {
    }

    public function findByDoctorIdAndStart(DoctorId $doctorId, DateTimeImmutable $start): ?Slot
    {
        $ormSlot = $this->entityManager
            ->getRepository(Slot::class)
            ->findOneBy([
                'doctorId' => $doctorId->value(),
                'start' => $start,
            ]);

        return $ormSlot ? SlotAdapter::fromDoctrine($ormSlot) : null;
    }

    public function save(Slot $slot, int $doctorId): void
    {
        $ormSlot = SlotAdapter::toDoctrine($slot, $doctorId);
        $this->entityManager->persist($ormSlot);
        $this->entityManager->flush();
    }
}
