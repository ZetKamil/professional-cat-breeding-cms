<?php

declare(strict_types=1);

namespace App\Http\Controllers\Frontend;

use App\Enums\AnimalStatus;
use App\Http\Controllers\Controller;
use App\Models\Animal;
use Illuminate\View\View;

class QuizController extends Controller
{
    /**
     * Display the Breed Matcher Quiz page.
     */
    public function index(): View
    {
        $availableAnimals = Animal::query()
            ->published()
            ->where('status', AnimalStatus::Available)
            ->where('type', \App\Enums\AnimalType::Cat)
            ->with(['media'])
            ->orderBy('sort_order', 'asc')
            ->get();

        return view('frontend.quiz', [
            'animals' => $availableAnimals,
        ]);
    }
}
