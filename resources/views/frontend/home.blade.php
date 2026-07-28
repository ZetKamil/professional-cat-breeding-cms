<x-frontend.shell
    title="Home"
    meta-description="Ontdek de nieuwste artikels en categorieën op onze blog."
>
    <x-frontend.home.welcome-slider :featured-posts="$featuredPosts" />

    <x-frontend.home.latest-news-marquee :latest-posts="$latestPosts" />

    <x-frontend.home.main-content
        :latest-posts="$latestPosts"
        :category-posts="$categoryPosts"
        :categories="$categories"
    />

    <x-frontend.home.video-post-area :video-posts="$videoPosts" />

    <x-frontend.home.editorial-area :editorial-posts="$editorialPosts" />
</x-frontend.shell>
