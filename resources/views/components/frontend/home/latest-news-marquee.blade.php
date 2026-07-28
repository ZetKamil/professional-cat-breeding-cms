@props([
    'latestPosts' => collect(),
])

<div class="latest-news-marquee-area">
    <div class="simple-marquee-container">
        <div class="marquee">
            <ul class="marquee-content-items">
                @forelse($latestPosts->take(6) as $post)
                    <li>
                        <a href="#">
                            <span class="latest-news-time">
                                {{ optional($post->published_at)->format('H:i') }}
                            </span>
                            {{ $post->title }}
                        </a>
                    </li>
                @empty
                    <li>
                        <a href="#">
                            <span class="latest-news-time">{{ now()->format('H:i') }}</span>
                            Nog geen recente artikels beschikbaar.
                        </a>
                    </li>
                @endforelse
            </ul>
        </div>
    </div>
</div>
