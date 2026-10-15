<?php

namespace App\Http\Controllers\Frontend;

use App\Enums\AnimalStatus;
use App\Http\Controllers\Controller;
use App\Models\Animal;
use Illuminate\View\View;

class HomeController extends Controller
{
    public function index(): View
    {
        $featuredAnimals = Animal::query()
            ->published()
            ->whereIn('status', [AnimalStatus::Available, AnimalStatus::Reserved])
            ->featured()
            ->with('media')
            ->orderedByStatus()
            ->take(6)
            ->get();

        return view('frontend.home', [
            'featuredAnimals' => $featuredAnimals,
        ]);
    }
}
