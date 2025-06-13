<?php

declare(strict_types=1);

namespace App\Slot\Domain\Entity;

use App\Slot\Domain\ValueObject\SlotId;
use App\Slot\Domain\ValueObject\SlotRange;
use DateTimeImmutable;

final class Slot
{
    private DateTimeImmutable $createdAt;

    private function __construct(
        private readonly SlotId $id,
        private SlotRange $range
    ) {
        $this->createdAt = new DateTimeImmutable();
    }

    public static function create(SlotId $id, SlotRange $range): self
    {
        return new self($id, $range);
    }

    public function id(): SlotId
    {
        return $this->id;
    }

    public function range(): SlotRange
    {
        return $this->range;
    }

    public function setRange(SlotRange $range): void
    {
        $this->range = $range;
    }

    public function isStale(): bool
    {
        return $this->createdAt < new DateTimeImmutable('-5 minutes');
    }
}
