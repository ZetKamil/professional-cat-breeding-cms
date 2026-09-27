<?php

declare(strict_types=1);

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\View\View;

class BlogController extends Controller
{
    /**
     * Wyświetl katalog artykułów na blogu z filtrowaniem i wyszukiwaniem.
     */
    public function index(Request $request): View
    {
        $query = Post::query()
            ->published()
            ->with(['user', 'categories', 'media']);

        // Filtr po kategorii (slug)
        if ($categorySlug = $request->get('category')) {
            $query->whereHas('categories', function ($q) use ($categorySlug) {
                $q->where('categories.slug', $categorySlug);
            });
        }

        // Wyszukiwanie tekstowe
        if ($search = $request->get('q')) {
            $query->search($search);
        }

        $posts = $query
            ->latest('published_at')
            ->paginate(9)
            ->withQueryString();

        $categories = Category::query()
            ->withCount(['posts' => function ($q) {
                $q->where('is_published', true)
                  ->whereNotNull('published_at')
                  ->where('published_at', '<=', now());
            }])
            ->orderBy('name')
            ->get();

        return view('frontend.blog.index', [
            'posts' => $posts,
            'categories' => $categories,
            'selectedCategory' => $request->get('category'),
            'searchQuery' => $request->get('q'),
        ]);
    }

    /**
     * Wyświetl szczegółowy artykuł blogowy (editorial view).
     */
    public function show(Post $post): View
    {
        if (! $post->is_published || ! $post->published_at || $post->published_at->isFuture()) {
            abort(404);
        }

        $post->load(['user', 'categories', 'media']);

        $relatedPosts = Post::query()
            ->published()
            ->with(['user', 'categories', 'media'])
            ->where('id', '!=', $post->id)
            ->whereHas('categories', function ($q) use ($post) {
                $q->whereIn('categories.id', $post->categories->pluck('id'));
            })
            ->latest('published_at')
            ->take(3)
            ->get();

        if ($relatedPosts->isEmpty()) {
            $relatedPosts = Post::query()
                ->published()
                ->with(['user', 'categories', 'media'])
                ->where('id', '!=', $post->id)
                ->latest('published_at')
                ->take(3)
                ->get();
        }

        return view('frontend.blog.show', [
            'post' => $post,
            'relatedPosts' => $relatedPosts,
        ]);
    }
}
