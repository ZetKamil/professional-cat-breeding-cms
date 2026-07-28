@props([
    'videoPosts' => collect(),
])

<section class="gazatte-video-post-area section_padding_100_70 bg-gray">
    <div class="container">
        <div class="row">
            @forelse($videoPosts as $post)
                <div class="col-12 col-md-6 col-lg-3">
                    <div class="single-video-post">
                        @if($post->media)
                            <div class="video-post-thumb">
                                <img src="{{ $post->media->url() }}" alt="{{ $post->title }}">
                            </div>
                        @else
                            <div class="video-post-thumb">
                                <img src="{{ asset('frontend/gazette/img/blog-img/bitcoin.jpg') }}" alt="{{ $post->title }}">
                            </div>
                        @endif

                        <h5>
                            <a href="#">{{ $post->title }}</a>
                        </h5>
                    </div>
                </div>
            @empty
                <div class="col-12">
                    <p>Geen video posts beschikbaar.</p>
                </div>
            @endforelse
        </div>
    </div>
</section>
