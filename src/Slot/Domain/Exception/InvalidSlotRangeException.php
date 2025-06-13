<?php

declare(strict_types=1);

namespace App\Slot\Domain\Exception;

use DomainException;

final class InvalidSlotRangeException extends DomainException
{
    public static function startIsAfterEnd(): self
    {
        return new self('Slot start time must be before end time.');
    }

    public static function tooShort(): self
    {
        return new self('Slot duration must be at least 5 minutes.');
    }
}
