<?php

declare(strict_types=1);

namespace App\Slot\Infrastructure\Adapter;


use App\Slot\Domain\Entity\Slot;
use App\Slot\Domain\ValueObject\SlotId;
use App\Slot\Domain\ValueObject\SlotRange;
use App\Slot\Infrastructure\Persistence\Doctrine\DoctrineSlot;

final class SlotAdapter
{
    public static function fromDoctrine(DoctrineSlot $doctrineSlot): Slot
    {

        $id = SlotId::self((string)$doctrineSlot->getId(),$doctrineSlot->getStart());
        $range = SlotRange::from(
            $doctrineSlot->getStart(),
            $doctrineSlot->getEnd()
        );

        return Slot::create($id, $range);
    }

    public static function toDoctrine(Slot $slot, int $doctorId): DoctrineSlot
    {
        return new DoctrineSlot(
            $doctorId,
            $slot->range()->start,
            $slot->range()->end
        );
    }
}
