<?php

declare(strict_types=1);

namespace Provider\DoctorExternal\Infrastructure\Api;

use App\Provider\DoctorExternal\Domain\Http\HttpFetcherInterface;
use App\Provider\DoctorExternal\Infrastructure\Api\DoctorExternalProvider;
use PHPUnit\Framework\TestCase;

final class DoctorExternalProviderTest extends TestCase
{
    public function test_fetch_all_doctors_returns_array(): void
    {
        $http = $this->createMock(HttpFetcherInterface::class);
        $http->method('get')->willReturn('[{"id":1,"name":"Dr. Test"}]');

        $provider = new DoctorExternalProvider($http);

        $result = $provider->fetchAllDoctors();

        $this->assertIsArray($result);
        $this->assertSame(1, count($result));
        $this->assertSame('Dr. Test', $result[0]['name']);
    }

    public function test_fetch_slots_for_doctor_returns_array(): void
    {
        $http = $this->createMock(HttpFetcherInterface::class);
        $http->method('get')->willReturn('[{"start":"2025-06-12T12:00:00Z","end":"2025-06-12T13:00:00Z"}]');

        $provider = new DoctorExternalProvider($http);

        $result = $provider->fetchSlotsForDoctor(1);

        $this->assertIsArray($result);
        $this->assertSame('2025-06-12T12:00:00Z', $result[0]['start']);
    }
}
