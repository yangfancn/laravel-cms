<?php

namespace App\Services\Crawler;

use GuzzleHttp\Psr7\Response;
use Illuminate\Support\Facades\Http;
use Spatie\Image\Image;
use Spatie\MediaLibrary\Downloaders\Downloader;
use Spatie\MediaLibrary\MediaCollections\Exceptions\UnreachableUrl;

class MediaDownloader implements Downloader
{
    protected static array $requestOptions = [];

    public static function setRequestOptions(array $requestOptions): void
    {
        self::$requestOptions = $requestOptions;
    }

    public function getTempFile(string $url): string
    {
        $temporaryFile = tempnam(sys_get_temp_dir(), 'media-library');

        /**
         * @var Response $response
         */
        $response = Http::withOptions(self::$requestOptions)
            ->throw(fn () => throw new UnreachableUrl($url))
            ->sink($temporaryFile)
            ->get($url);

        if (str_starts_with($response->getHeaderLine('Content-Type'), 'image')) {
            $this->compressImage($temporaryFile);
        }

        return $temporaryFile;
    }

    private function compressImage(string $path): void
    {
        if (! $info = getimagesize($path)) {
            return;
        }

        [$w, $_, $type] = $info;

        if ($type === IMAGETYPE_GIF) {
            return;
        }

        $max_width = env('MEDIA_DOWNLOAD_MAX_WIDTH');

        if ($w < $max_width) {
            return;
        }

        Image::load($path)
            ->width($max_width)
            ->quality(env('MEDIA_DOWNLOAD_IMAGE_QUALITY'))
            ->format(image_type_to_extension($type, false))
            ->save();
    }
}
