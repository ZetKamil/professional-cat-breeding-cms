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
            ->whereIn('status', [AnimalStatus::Available, AnimalStatus::Reserved])
            ->with(['media'])
            ->orderByRaw("CASE WHEN status = 'available' THEN 1 ELSE 2 END ASC")
            ->orderBy('sort_order', 'asc')
            ->get();

        return view('frontend.quiz', [
            'animals' => $availableAnimals,
        ]);
    }
}
