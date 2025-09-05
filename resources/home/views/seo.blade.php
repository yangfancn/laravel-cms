<title>
  {{ $seo["title"] }}@if ($with_site_name)
    - {{ $site["name"] }}
  @endif
</title>
@if (isset($seo["keywords"]))
  <meta name="keywords" content="{{ $seo["keywords"] }}">
@endif
<meta name="description" content="{{ $seo["description"] ?? $site->meta->name }}">
@foreach ($seo["others"] ?? [] as $meta)
  <meta name="{{ $meta["name"] }}" content="{{ $meta["value"] }}" />
@endforeach
