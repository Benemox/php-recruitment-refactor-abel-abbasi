<?php

declare(strict_types=1);

namespace App\Provider\DoctorExternal\Domain\Exception;

use DomainException;

final class DoctorExternalProviderException extends DomainException
{
    public static function failedToFetch(string $url): self
    {
        return new self("Failed to fetch data from external provider at: {$url}");
    }

    public static function invalidResponse(string $json): self
    {
        return new self("Invalid JSON response from external provider: {$json}");
    }
}
