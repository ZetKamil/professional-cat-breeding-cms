@props([
    'featuredPosts' => collect(),
])

<section class="welcome-blog-post-slide owl-carousel">
    @forelse($featuredPosts as $post)
        <div class="single-blog-post-slide bg-img background-overlay-5"
             style="background-image: url('{{ $post->media ? $post->media->url() : asset('frontend/gazette/img/blog-img/1.jpg') }}');">
            <div class="single-blog-post-content">
                <div class="tags">
                    @foreach($post->categories->take(4) as $category)
                        <a href="#">{{ $category->name }}</a>
                    @endforeach
                </div>

                <h3>
                    <a href="#" class="font-pt">{{ $post->title }}</a>
                </h3>

                <div class="date">
                    <a href="#">{{ optional($post->published_at)->format('M d, Y') }}</a>
                </div>
            </div>
        </div>
    @empty
        <div class="single-blog-post-slide bg-img background-overlay-5"
             style="background-image: url('{{ asset('frontend/gazette/img/blog-img/1.jpg') }}');">
            <div class="single-blog-post-content">
                <div class="tags">
                    <a href="#">Frontend</a>
                </div>

                <h3>
                    <a href="#" class="font-pt">Nog geen gepubliceerde posts beschikbaar</a>
                </h3>

                <div class="date">
                    <a href="#">{{ now()->format('M d, Y') }}</a>
                </div>
            </div>
        </div>
    @endforelse
</section>
