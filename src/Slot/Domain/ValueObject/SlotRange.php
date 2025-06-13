<?php

declare(strict_types=1);

namespace App\Slot\Domain\ValueObject;

use App\Slot\Domain\Exception\InvalidSlotRangeException;
use DateTimeImmutable;

final readonly class SlotRange
{
    private function __construct(
        public \DateTimeImmutable $start,
        public \DateTimeImmutable $end
    ) {}

    public static function from(\DateTimeInterface $start, \DateTimeInterface $end): self
    {
        if ($start >= $end) {
            throw InvalidSlotRangeException::startIsAfterEnd();
        }

        if ($end->getTimestamp() - $start->getTimestamp() < 300) {
            throw InvalidSlotRangeException::tooShort();
        }


        return new self(
            \DateTimeImmutable::createFromInterface($start),
            \DateTimeImmutable::createFromInterface($end)
        );
    }
}

