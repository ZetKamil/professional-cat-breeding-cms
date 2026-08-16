<?php echo '<?xml version="1.0" encoding="UTF-8"?>'; ?>
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">

    {{-- Static public pages --}}
    @foreach($staticPages as $page)
    <url>
        <loc>{{ $page['url'] }}</loc>
        <lastmod>{{ $page['lastmod'] }}</lastmod>
        <changefreq>{{ $page['changefreq'] }}</changefreq>
        <priority>{{ $page['priority'] }}</priority>
    </url>
    @endforeach

    {{-- Animals (published) --}}
    @foreach($animals as $animal)
    <url>
        <loc>{{ $baseUrl . '/koty/' . $animal->slug }}</loc>
        <lastmod>{{ $animal->updated_at ? $animal->updated_at->toDateString() : ($animal->published_at ? $animal->published_at->toDateString() : now()->toDateString()) }}</lastmod>
        <changefreq>monthly</changefreq>
        <priority>0.8</priority>
    </url>
    @endforeach

    {{-- Blog posts (published) --}}
    @foreach($posts as $post)
    <url>
        <loc>{{ $baseUrl . '/blog/' . $post->slug }}</loc>
        <lastmod>{{ $post->updated_at ? $post->updated_at->toDateString() : ($post->published_at ? $post->published_at->toDateString() : now()->toDateString()) }}</lastmod>
        <changefreq>monthly</changefreq>
        <priority>0.7</priority>
    </url>
    @endforeach

</urlset>
