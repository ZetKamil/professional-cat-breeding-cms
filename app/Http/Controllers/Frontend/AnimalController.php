<?php

declare(strict_types=1);

namespace App\Http\Controllers\Frontend;

use App\Enums\AnimalGender;
use App\Enums\AnimalStatus;
use App\Http\Controllers\Controller;
use App\Models\Animal;
use Illuminate\Http\Request;
use Illuminate\View\View;

/**
 * Controller responsible for the public presentation of animals (cats).
 *
 * Supports filtering by our 3 breeds (Kot Bengalski, Kot Brytyjski, Kot Syjamski),
 * lifecycle status (Available, Breeding, Reserved, etc.), gender, and search.
 */
class AnimalController extends Controller
{
    /**
     * Known breeds in our cattery for filtering navigation.
     */
    private const BREEDS = [
        'Kot Bengalski',
        'Kot Brytyjski',
        'Kot Syjamski',
    ];

    /**
     * Display a paginated listing of published animals with filters.
     */
    public function index(Request $request): View
    {
        $query = Animal::query()
            ->published()
            ->with('media')
            ->when($request->filled('breed'), function ($q) use ($request) {
                $breedInput = $request->string('breed')->toString();
                $breedMap = [
                    'bengal' => 'Kot Bengalski',
                    'bengalskie' => 'Kot Bengalski',
                    'british' => 'Kot Brytyjski',
                    'brytyjskie' => 'Kot Brytyjski',
                    'brytyjczyki' => 'Kot Brytyjski',
                    'siamese' => 'Kot Syjamski',
                    'syjamskie' => 'Kot Syjamski',
                ];
                $breed = $breedMap[$breedInput] ?? $breedInput;
                $q->where('breed', '=', $breed);
            })
            ->when($request->filled('status'), function ($q) use ($request) {
                $q->statusFilter($request->string('status')->toString());
            })
            ->when($request->filled('gender'), function ($q) use ($request) {
                $q->genderFilter($request->string('gender')->toString());
            })
            ->when($request->filled('q'), function ($q) use ($request) {
                $q->search($request->string('q')->toString());
            })
            ->orderBy('sort_order', 'asc')
            ->latest('published_at');

        $animals = $query->paginate(12)->withQueryString();

        return view('frontend.animals.index', [
            'animals' => $animals,
            'breeds' => self::BREEDS,
            'currentBreed' => $request->string('breed')->toString(),
            'currentStatus' => $request->string('status')->toString(),
            'currentGender' => $request->string('gender')->toString(),
            'searchQuery' => $request->string('q')->toString(),
            'statuses' => AnimalStatus::cases(),
            'genders' => AnimalGender::cases(),
        ]);
    }

    /**
     * Display the specified animal profile with gallery and pedigree.
     */
    public function show(Animal $animal): View
    {
        // Ensure only published animals are accessible to the public
        if (! $animal->is_published || ! $animal->published_at) {
            abort(404);
        }

        $animal->load([
            'media',
            'gallery',
            'mother',
            'father',
            'childrenAsMother',
            'childrenAsFather',
        ]);

        $relatedAnimals = Animal::query()
            ->published()
            ->where('id', '!=', $animal->id)
            ->where(function ($q) use ($animal) {
                $q->where('breed', '=', $animal->breed)
                  ->orWhere('status', '=', AnimalStatus::Available);
            })
            ->with('media')
            ->orderBy('sort_order', 'asc')
            ->take(3)
            ->get();

        return view('frontend.animals.show', [
            'animal' => $animal,
            'relatedAnimals' => $relatedAnimals,
        ]);
    }
}
