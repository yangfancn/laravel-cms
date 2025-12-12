<?php

namespace App\Services;

use App\Models\Site;
use App\Models\Traits\Metable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\View;
use Ramsey\Uuid\Exception\InvalidArgumentException;

/**
 * Seo class for managing SEO metadata.
 */
class Seo
{
    protected array $data = [];

    protected Site $site;

    protected bool $with_site_name = true;

    public function __construct()
    {
        $this->site = View::shared('site', fn () => Site::first());
    }

    /**
     * Set seo data by model.
     * Model mush use App\Models\Traits\Metable trait.
     *
     * @throws \Ramsey\Uuid\Exception\InvalidArgumentException
     */
    public function model(?Model $model = null): self
    {
        if ($model === null) {
            $model = $this->site;
            $this->with_site_name = false;
        }

        if (! in_array(Metable::class, class_uses($model))) {
            throw new InvalidArgumentException('$model must use App\Models\Traits\Metable::class trait');
        }

        $this->data = $model->meta?->toArray() ?: [];

        return $this;
    }

    /**
     * Set SEO data manually.
     */
    public function seo(
        string $title,
        ?string $keywords = null,
        ?string $description = null,
        ?string $url = null,
        ?string $image = null
    ): self {
        $this->data = compact('title', 'keywords', 'description', 'url', 'image');

        return $this;
    }

    /**
     * Seo title without site name.
     */
    public function withoutSiteName(): self
    {
        $this->with_site_name = false;

        return $this;
    }

    /**
     * Set noindex meta.
     */
    public function noIndex(): self
    {
        $this->data['others'][] = [
            'name' => 'robots',
            'value' => 'noindex, nofollow',
        ];

        return $this;
    }

    /**
     * Generate SEO meta tags.
     *
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     */
    public function generate(): string
    {
        return view('home::seo', [
            'seo' => $this->data,
            'with_site_name' => $this->with_site_name,
            'site' => $this->site,
        ])->render();
    }
}
