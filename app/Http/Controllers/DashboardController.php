<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Enums\AnimalStatus;
use App\Models\Animal;
use App\Models\Category;
use App\Models\Media;
use App\Models\Post;
use App\Models\User;
use Illuminate\Support\Facades\Gate;
use Illuminate\View\View;

class DashboardController extends Controller
{
    /**
     * Display the main admin dashboard with real-time statistics.
     */
    public function index(): View
    {
        Gate::authorize('view-backend-dashboard');

        // Real data queries
        $totalAnimals = Animal::count();
        $availableAnimals = Animal::where('status', AnimalStatus::Available)->count();
        $reservedAnimals = Animal::where('status', AnimalStatus::Reserved)->count();
        $breedingAnimals = Animal::where('status', AnimalStatus::Breeding)->count();
        $soldAnimals = Animal::where('status', AnimalStatus::Sold)->count();

        $totalPosts = Post::count();
        $publishedPosts = Post::where('is_published', true)->count();
        $draftPosts = Post::where('is_published', false)->count();

        $totalCategories = Category::count();
        $totalMedia = Media::count();
        $totalUsers = User::count();

        $recentAnimals = Animal::with('media')
            ->latest()
            ->take(6)
            ->get();

        $recentPosts = Post::with('category')
            ->latest()
            ->take(5)
            ->get();

        return view('backend.dashboard', compact(
            'totalAnimals',
            'availableAnimals',
            'reservedAnimals',
            'breedingAnimals',
            'soldAnimals',
            'totalPosts',
            'publishedPosts',
            'draftPosts',
            'totalCategories',
            'totalMedia',
            'totalUsers',
            'recentAnimals',
            'recentPosts'
        ));
    }
}
