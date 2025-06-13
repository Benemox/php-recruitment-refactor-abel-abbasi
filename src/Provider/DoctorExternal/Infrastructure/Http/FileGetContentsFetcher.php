<?php

namespace App\Provider\DoctorExternal\Infrastructure\Http;

use App\Provider\DoctorExternal\Domain\Http\HttpFetcherInterface;
final class FileGetContentsFetcher implements HttpFetcherInterface
{
    public function get(string $url): string
    {
        $auth = base64_encode('docplanner:docplanner');

        $context = stream_context_create([
            'http' => [
                'header' => 'Authorization: Basic ' . $auth,
            ],
        ]);

        return @file_get_contents($url, false, $context) ?: '[]';
    }
}
