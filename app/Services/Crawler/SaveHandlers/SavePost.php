<?php

namespace App\Services\Crawler\SaveHandlers;

use App\Models\Post;
use App\Services\Crawler\FilterHandlers\FilterPost;
use App\Services\Crawler\MediaDownloader;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Symfony\Component\DomCrawler\Crawler;
use Throwable;

class SavePost extends SaveAbstract
{
    /**
     * @var string[]
     */
    protected array $imgSrcAttrs = [];

    public function __construct(string $imgAttr = 'src')
    {
        $this->imgSrcAttrs = explode('|', $imgAttr);
    }

    public function save(array $data, string $url, array $requestOptions = []): bool|Model
    {
        if (
            ! isset($data['title'])
            || ! $data['content']
            || (new FilterPost)->filter(['link' => $url])
        ) {
            return false;
        }

        $data['original_url'] = $data['link'];

        $post = new Post;
        $post->fill($data);

        if (isset($data['created_at']) && $data['created_at']) {
            $post->created_at = $data['created_at'];
        }

        try {
            $post->saveOrFail();
        } catch (Throwable $exception) {
            Log::error('Save Post Failed: '.$exception->getMessage());

            return false;
        }

        // media
        MediaDownloader::setRequestOptions($requestOptions);

        if (isset($data['thumb']) && $data['thumb']) {
            $post->addMediaFromUrl($data['thumb'])
                ->toMediaCollection('thumb');
        }

        $post->moveImagesToMedia('content');

        $post->categories()->sync($data['category_id']);

        if (isset($data['tags'])) {
            $post->syncTagsByName($data['tags']);
        }

        return true;
    }

    private function extractImageUrl(Crawler $node, array $attributes): ?string
    {
        foreach ($attributes as $attr) {
            $source = $node->attr($attr);
            if ($source && ! str_starts_with($source, 'data:image')) {
                return $source;
            }
        }

        return null;
    }

    private function updateImageNode(Crawler $node, Media $media): void
    {
        $imgNode = $node->getNode(0);
        if ($imgNode instanceof \DOMElement) {
            $imgNode->setAttribute('src', $media->getPath());
            $imgNode->setAttribute('data-media', $media->id);
            foreach ($this->imgSrcAttrs as $attr) {
                if ($attr !== 'src') {
                    $imgNode->removeAttribute($attr);
                }
            }
        }
    }
}
