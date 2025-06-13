<?php

declare(strict_types=1);

namespace App\Provider\DoctorExternal\Infrastructure\Api;

use App\Provider\DoctorExternal\Domain\Http\HttpFetcherInterface;
use App\Provider\DoctorExternal\Domain\Repository\DoctorExternalProviderInterface;
use JsonException;

final class DoctorExternalProvider implements DoctorExternalProviderInterface
{
    private const BASE_URL = 'http://localhost:2137/api/doctors';

    public function __construct(private readonly HttpFetcherInterface $fetcher)
    {
    }

    public function fetchAllDoctors(): array
    {
        $json = $this->fetcher->get(self::BASE_URL);
        return $this->decodeJson($json);
    }

    public function fetchSlotsForDoctor(int|string $doctorId): array
    {
        $json = $this->fetcher->get(self::BASE_URL . '/' . $doctorId . '/slots');
        return $this->decodeJson($json);
    }

    /**
     * @throws JsonException
     */
    private function decodeJson(string $json): array
    {
        return json_decode($json, true, 512, JSON_THROW_ON_ERROR);
    }
}

