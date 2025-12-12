<?php

namespace App\Services\Crawler\SaveHandlers;

use App\Models\Post;
use App\Models\Tag;
use App\Services\Crawler\FilterHandlers\FilterPost;
use App\Services\Crawler\ImageConfig;
use App\Services\Crawler\ImageDownload;
use Illuminate\Database\Eloquent\Model;
use Throwable;

class SavePost extends SaveAbstract
{
    public string $imgAttr;

    public function __construct($imgAttr = 'src')
    {
        $this->imgAttr = $imgAttr;
    }

    public function save(array $data, string $url, array $requestOptions = []): bool|Model
    {
        if (! isset($data['title']) || (new FilterPost)->filter(['link' => $url])) {
            return false;
        }

        $downloadHandler = new ImageDownload(new ImageConfig(requestOptions: $requestOptions));

        // thumb
        if (array_key_exists('thumb', $data) && $data['thumb']) {
            $data['thumb'] = $downloadHandler->download($data['thumb'])->public_path;
        }

        $data['content'] = $downloadHandler->downloadFromHtml($data['content'], attr: $this->imgAttr);

        $data['original_url'] = $data['link'];

        $post = new Post;
        $post->fill($data);

        if (isset($data['created_at']) && $data['created_at']) {
            $post->created_at = $data['created_at'];
        }

        try {
            $post->saveOrFail();
        } catch (Throwable $exception) {
            dump($exception->getMessage());

            return false;
        }

        if (isset($data['tags'])) {
            $exists = Tag::whereIn('name', $data['tags'])->get();
            $diff = array_diff($data['tags'], $exists->pluck('name')->all());
            Tag::insertOrIgnore(array_map(function ($name) {
                return ['name' => $name];
            }, $diff));
            $insert_ids = Tag::whereIn('name', $diff)->pluck('id')->all();
            $tag_ids = array_merge($exists->pluck('id')->all(), $insert_ids);
            $post->tags()->sync($tag_ids);
        }

        return true;
    }
}
