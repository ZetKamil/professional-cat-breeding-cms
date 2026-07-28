@props([
    'latestPosts' => collect(),
    'categoryPosts' => collect(),
    'categories' => collect(),
])

<section class="main-content-wrapper section_padding_100">
    <div class="container">
        <div class="row">
            <div class="col-12 col-lg-9">
                @php
                    $welcomePost = $latestPosts->first();
                    $todayPosts = $latestPosts->slice(1, 2);
                    $dontMissPosts = $latestPosts->slice(3, 3);
                @endphp

                @if($welcomePost)
                    <div class="gazette-welcome-post">
                        <div class="gazette-post-tag">
                            @foreach($welcomePost->categories->take(1) as $category)
                                <a href="#">{{ $category->name }}</a>
                            @endforeach
                        </div>

                        <h2 class="font-pt">{{ $welcomePost->title }}</h2>

                        <p class="gazette-post-date">
                            {{ optional($welcomePost->published_at)->format('F d, Y') }}
                        </p>

                        @if($welcomePost->media)
                            <div class="blog-post-thumbnail my-4">
                                <img src="{{ $welcomePost->media->url() }}" alt="{{ $welcomePost->title }}">
                            </div>
                        @endif

                        <p>
                            {{ $welcomePost->excerpt ?: \Illuminate\Support\Str::limit(strip_tags($welcomePost->body), 220) }}
                        </p>
                    </div>
                @endif

                <div class="row">
                    <div class="col-12 col-md-6">
                        <div class="gazette-todays-post section_padding_100_50">
                            @foreach($todayPosts as $post)
                                <div class="gazette-single-todays-post d-md-flex mb-30">
                                    @if($post->media)
                                        <div class="todays-post-thumb">
                                            <img src="{{ $post->media->url() }}" alt="{{ $post->title }}">
                                        </div>
                                    @endif

                                    <div class="todays-post-content">
                                        <div class="gazette-post-tag">
                                            @foreach($post->categories->take(1) as $category)
                                                <a href="#">{{ $category->name }}</a>
                                            @endforeach
                                        </div>

                                        <h4>
                                            <a href="#" class="font-pt mb-2">{{ $post->title }}</a>
                                        </h4>

                                        <span class="gazette-post-date mb-2 d-block">
                                            {{ optional($post->published_at)->format('F d, Y') }}
                                        </span>

                                        <p class="mb-0">
                                            {{ $post->excerpt ?: \Illuminate\Support\Str::limit(strip_tags($post->body), 100) }}
                                        </p>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <div class="col-12 col-md-6">
                        <div class="gazette-todays-post section_padding_100_50">
                            @foreach($dontMissPosts as $post)
                                <div class="gazette-single-todays-post d-md-flex mb-30">
                                    @if($post->media)
                                        <div class="todays-post-thumb">
                                            <img src="{{ $post->media->url() }}" alt="{{ $post->title }}">
                                        </div>
                                    @endif

                                    <div class="todays-post-content">
                                        <h4>
                                            <a href="#" class="font-pt mb-2">{{ $post->title }}</a>
                                        </h4>

                                        <span class="gazette-post-date mb-2 d-block">
                                            {{ optional($post->published_at)->format('F d, Y') }}
                                        </span>

                                        <p class="mb-0">
                                            {{ $post->excerpt ?: \Illuminate\Support\Str::limit(strip_tags($post->body), 100) }}
                                        </p>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>

                <div class="gazette-catagory-posts">
                    <div class="row">
                        @foreach($categoryPosts as $post)
                            <div class="col-12 col-md-6">
                                <div class="gazette-single-catagory-post d-flex flex-wrap mb-30">
                                    @if($post->media)
                                        <div class="single-catagory-post-thumb">
                                            <img src="{{ $post->media->url() }}" alt="{{ $post->title }}">
                                        </div>
                                    @endif

                                    <div class="single-catagory-post-content">
                                        <div class="gazette-post-tag">
                                            @foreach($post->categories->take(1) as $category)
                                                <a href="#">{{ $category->name }}</a>
                                            @endforeach
                                        </div>

                                        <h5>
                                            <a href="#" class="font-pt">{{ $post->title }}</a>
                                        </h5>

                                        <span class="gazette-post-date">
                                            {{ optional($post->published_at)->format('F d, Y') }}
                                        </span>

                                        <p>
                                            {{ $post->excerpt ?: \Illuminate\Support\Str::limit(strip_tags($post->body), 110) }}
                                        </p>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <div class="col-12 col-lg-3 col-md-6">
                <div class="sidebar-area">
                    <div class="breaking-news-widget">
                        <div class="widget-title">
                            <h5>Categories</h5>
                        </div>

                        @forelse($categories as $category)
                            <div class="single-breaking-news-widget">
                                <a href="#" class="font-pt">{{ $category->name }}</a>
                                <span>{{ $category->posts_count }} post(s)</span>
                            </div>
                        @empty
                            <p>Geen categorieën beschikbaar.</p>
                        @endforelse
                    </div>

                    <div class="donnot-miss-widget">
                        <div class="widget-title">
                            <h5>Advert</h5>
                        </div>

                        <div class="single-dont-miss-post-thumb">
                            <img src="{{ asset('frontend/gazette/img/bg-img/add.png') }}" alt="advertentie">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
