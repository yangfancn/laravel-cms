<title>{{ $seo['title'] . ($with_site_name ? " - {$site->name}" : '') }}</title>
@if (isset($seo['keywords']))
  <meta
    name="keywords"
    content="{{ $seo['keywords'] }}"
  >
@endif
@if (isset($ld['url']))
  <link
    rel="canonical"
    href="{!! $ld['url'] !!}"
  >
@endif
<meta
  name="description"
  content="{{ $seo['description'] ?? $site->meta->name }}"
>
@foreach ($seo['others'] ?? [] as $meta)
  @if (str_starts_with($meta['name'], 'og:'))
    <meta
      property="{{ $meta['name'] }}"
      content="{{ $meta['value'] }}"
    />
  @else
    <meta
      name="{{ $meta['name'] }}"
      content="{{ $meta['value'] }}"
    />
  @endif
@endforeach
@if ($ld)
  <script type="application/ld+json">
  {!! json_encode($ld, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) !!}
  </script>
@endif
