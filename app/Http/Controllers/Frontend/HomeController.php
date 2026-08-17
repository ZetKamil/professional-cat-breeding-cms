<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Animal;
use App\Models\Post;
use Illuminate\View\View;

class HomeController extends Controller
{
    public function index(): View
    {
        $featuredAnimals = Animal::query()
            ->published()
            ->featured()
            ->with('media')
            ->orderedByStatus()
            ->take(3)
            ->get();

        $latestPosts = Post::query()
            ->with(['user', 'categories', 'media'])
            ->where('is_published', true)
            ->whereNotNull('published_at')
            ->latest('published_at')
            ->take(3)
            ->get();

        return view('frontend.home', [
            'featuredAnimals' => $featuredAnimals,
            'latestPosts' => $latestPosts,
        ]);
    }
}
