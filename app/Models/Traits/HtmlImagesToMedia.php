<?php

namespace App\Models\Traits;

use DOMElement;
use Exception;
use Illuminate\Support\Facades\Storage;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Symfony\Component\DomCrawler\Crawler;

trait HtmlImagesToMedia
{
    /**
     * move html images to Media and save
     *
     * @throws Exception
     */
    public function moveImagesToMedia(
        string $attribute,
        string $collection = 'default',
        string $imgSrcAttr = 'src',
        ?array $requestHeaders = null
    ): self {
        if (! $this->exists) {
            throw new Exception('Model must be saved before moving images to media.');
        }

        $crawler = new Crawler($this->$attribute);
        $images = $crawler->filter('img');

        if (! $images->count()) {
            return $this;
        }

        $existsMedia = $this->getMedia($collection)
            ->map(fn (Media $m) => ['id' => $m->id, 'url' => $m->getUrl()]);

        $srcAttributes = explode('|', $imgSrcAttr);

        $keepIds = [];

        foreach ($images as $img) {
            if (! $path = $this->extractSrc($img, $srcAttributes)) {
                continue;
            }

            if ($ex = $existsMedia->where('url', $path)->first()) {
                $keepIds[] = $ex['id'];

                continue;
            }

            if (str_starts_with($path, 'http') || str_starts_with($path, '//')) {
                $media = $this->addMediaFromUrl($path)
                    ->addCustomHeaders($requestHeaders ?: [])
                    ->toMediaCollection($collection);
            } else {
                $fullPath = app()->publicPath($path);
                if (! file_exists($fullPath)) {
                    continue;
                }

                // 判断是不是临时文件并移动
                $media = $this->addMedia($fullPath)
                    ->preservingOriginal(! str_starts_with($path, Storage::disk('temp')->url('')))
                    ->toMediaCollection($collection);
            }

            $keepIds[] = $media->id;

            $this->updateImageNode($img, $media, $srcAttributes);
        }

        $this->getMedia($collection)
            ->each(fn (Media $m) => ! \in_array($m->id, $keepIds, true) && $m->delete());

        $this->$attribute = $crawler->filter('body')->html();

        if ($this->isDirty($attribute)) {
            $this->saveQuietly();
        }

        return $this;
    }

    private function extractSrc(DOMElement $node, array $attributes): ?string
    {
        foreach ($attributes as $attr) {
            $source = $node->getAttribute($attr);
            if ($source && ! str_starts_with($source, 'data:image')) {
                return $source;
            }
        }

        return null;
    }

    private function updateImageNode(DOMElement $node, Media $media, array $srcAttributes): void
    {
        $node->setAttribute('src', $media->getUrl());
        $node->setAttribute('data-media', $media->id);
        foreach ($srcAttributes as $attr) {
            if ($attr !== 'src') {
                $node->removeAttribute($attr);
            }
        }
    }
}
