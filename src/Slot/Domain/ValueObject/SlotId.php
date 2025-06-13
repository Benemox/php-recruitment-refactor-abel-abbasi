<?php

declare(strict_types=1);

namespace App\Slot\Domain\ValueObject;

final readonly class SlotId
{
    public function __construct(private string $value)
    {
    }

    public static function self(int|string $doctorId, \DateTimeInterface $start): self
    {
        $raw = $doctorId . '|' . $start->format(\DateTimeInterface::ATOM);
        return new self(sha1($raw));
    }

    public function value(): string
    {
        return $this->value;
    }

    public function equals(SlotId $other): bool
    {
        return $this->value === $other->value;
    }
}
