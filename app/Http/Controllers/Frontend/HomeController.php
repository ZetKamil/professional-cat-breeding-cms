<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Animal;
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

        return view('frontend.home', [
            'featuredAnimals' => $featuredAnimals,
        ]);
    }
}
