<?php

namespace App\Services\Crawler;

class ImageDownloadResult
{
    public function __construct(
        public string  $remote_url,
        public ?string $full_path = null,
        public ?string $public_path = null,
        public ?string $error = null
    ) {}

    public function __toString(): string
    {
        return $this->public_path;
    }
}
