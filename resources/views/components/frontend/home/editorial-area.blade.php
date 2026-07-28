@props([
    'editorialPosts' => collect(),
])

<section class="gazatte-editorial-area section_padding_80 bg-dark">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <div class="editorial-post-slides owl-carousel">
                    @forelse($editorialPosts as $post)
                        <div class="editorial-post-single-slide">
                            @if($post->media)
                                <div class="editorial-post-thumb">
                                    <img src="{{ $post->media->url() }}" alt="{{ $post->title }}">
                                </div>
                            @endif

                            <div class="editorial-post-content">
                                <div class="gazette-post-tag">
                                    @foreach($post->categories->take(1) as $category)
                                        <a href="#">{{ $category->name }}</a>
                                    @endforeach
                                </div>

                                <h4>
                                    <a href="#" class="font-pt">{{ $post->title }}</a>
                                </h4>

                                <p>
                                    {{ $post->excerpt ?: \Illuminate\Support\Str::limit(strip_tags($post->body), 100) }}
                                </p>
                            </div>
                        </div>
                    @empty
                        <div class="editorial-post-single-slide">
                            <div class="editorial-post-content">
                                <h4>
                                    <a href="#" class="font-pt">Nog geen editorial posts beschikbaar</a>
                                </h4>
                            </div>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</section>
