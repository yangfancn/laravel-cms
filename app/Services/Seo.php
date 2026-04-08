<?php

namespace App\Services;

use App\Models\Category;
use App\Models\Site;
use App\Models\Traits\Metable;
use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\View;
use Ramsey\Uuid\Exception\InvalidArgumentException;

/**
 * Seo class for managing SEO metadata.
 */
class Seo
{
    protected array $data = [];

    public Site $site;

    protected bool $with_site_name = true;

    protected array $ld_data = [];

    public function __construct()
    {
        $this->site = View::shared('site') ?? Site::first();
        View::share('active_category', null);
    }

    /**
     * Set seo data by model.
     * Model mush use App\Models\Traits\Metable trait.
     *
     * @throws InvalidArgumentException
     */
    public function model(?Model $model = null): self
    {
        if ($model === null) {
            $model = $this->site;
            $this->with_site_name = false;
            $this->ld_data = [
                '@type' => 'WebSite',
                'name' => $this->site->name,
                'alternateName' => $this->site->name,
                'url' => route('home', absolute: true),
            ];
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

    public function activeCategory(string|Category $category): self
    {
        View::share('active_category', is_string($category) ? $category : $category->directory);

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

    public function ldJson(array $data, bool $partOfIndex = false): self
    {
        $this->ld_data = [
            ...$data,
            ...$partOfIndex
            ? [
                'isPartOf' => [
                    '@type' => 'WebSite',
                    'name' => $this->site->name,
                    'url' => route('home'),
                ],
            ]
            : [],
        ];

        return $this;
    }

    public function getSite(): Site
    {
        return $this->site;
    }

    /**
     * Generate SEO meta tags.
     *
     * @throws BindingResolutionException
     */
    public function generate(): string
    {
        View::share([
            'seo' => $this->data,
            'with_site_name' => $this->with_site_name,
            'site' => $this->site,
            'ld' => [
                '@context' => 'https://schema.org',
                ...$this->ld_data,
            ],
        ]);

        return view('home::seo')->render();
    }
}
