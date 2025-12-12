<?php

namespace App\Services\Crawler;

class ImageConfig
{
    public function __construct(
        public readonly ?int $maxWidth = 1200,
        public readonly ?int $maxHeight = null,
        public readonly string $disk = 'public',
        public readonly string $saveDir = 'images',
        public readonly ?string $dateDirFormat = 'Ymd',
        public readonly ?float $aspectRatio = null,
        public readonly array $requestOptions = [
            'timeout' => 15,
            'verify' => false,
            'headers' => [
                'Accept' => 'text/html,application/xhtml+xml,application/xml;q=0.9,image/avif,image/webp,image/apng,*/*;q=0.8',
                'User-Agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36',
            ],
            'decode_content' => false,
        ]
    ) {}
}
