<?php

declare(strict_types=1);

namespace App\Doctor\Domain\Exception;

final class InvalidDoctorNameException extends \DomainException
{
    public static function empty(): self
    {
        return new self('Doctor name cannot be empty.');
    }

    public static function tooShort(): self
    {
        return new self('Doctor name is too short.');
    }

    public static function invalidCharacters(): self
    {
        return new self('Doctor name contains invalid characters.');
    }
}
