<?php

namespace App\Provider\DoctorExternal\Domain\Http;

interface HttpFetcherInterface
{
    public function get(string $url): string;
}