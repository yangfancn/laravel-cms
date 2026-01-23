<?php

namespace App\Models\Traits;

use Illuminate\Support\Facades\Storage;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

trait SyncMedia
{
    public function syncUploadedMedia(string|array|null $paths, string $collection = 'default'): static
    {
        if ($paths === null || $paths === '') {
            $this->clearMediaCollection($collection);

            return $this;
        }

        \is_string($paths) && $paths = [$paths];

        $exists = $this->getMedia($collection);
        $newMedias = [];

        foreach ($paths as $path) {
            if ($media = $exists->first(fn (Media $media) => $media->getUrl() === $path)) {
                $newMedias[] = $media->setVisible(['id'])->toArray();
            } elseif (Storage::disk('local')->exists($path)) {
                $media = $this->addMedia(Storage::disk('local')->path($path))
                    ->toMediaCollection($collection);
                $newMedias[] = $media->setVisible(['id'])->toArray();
            }
        }
        $this->removeMediaItemsNotPresentInArray($newMedias, $collection);

        return $this;
    }
}
