{!! $namespace !!}
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
  @foreach ($urls as $item)
    <url>
      <loc>{{ $item['url']  }}</loc>
      <lastmod>{{ $item['lastMod'] }}</lastmod>
    </url>
  @endforeach
</urlset>
