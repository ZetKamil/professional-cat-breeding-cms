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
        $scalarInput = static fn (string $key): string => is_scalar($val = $request->input($key))
            ? trim((string) $val)
            : '';

        $breedInput = $scalarInput('breed');
        $statusInput = $scalarInput('status');
        $genderInput = $scalarInput('gender');
        $searchInput = $scalarInput('q');

        $query = Animal::query()
            ->published()
            ->with('media')
            ->when($breedInput !== '', function ($q) use ($breedInput) {
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
            ->when($statusInput !== '', function ($q) use ($statusInput) {
                $q->statusFilter($statusInput);
            })
            ->when($genderInput !== '', function ($q) use ($genderInput) {
                $q->genderFilter($genderInput);
            })
            ->when($searchInput !== '', function ($q) use ($searchInput) {
                $q->search($searchInput);
            })
            ->orderedByStatus();

        $animals = $query->paginate(24)->withQueryString();

        return view('frontend.animals.index', [
            'animals' => $animals,
            'breeds' => self::BREEDS,
            'currentBreed' => $breedInput,
            'currentStatus' => $statusInput,
            'currentGender' => $genderInput,
            'searchQuery' => $searchInput,
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
        if (! $animal->is_published || ($animal->published_at && $animal->published_at->isFuture())) {
            abort(404);
        }

        $animal->load([
            'media',
            'gallery',
            'mother.media',
            'mother.mother',
            'mother.father',
            'father.media',
            'father.mother',
            'father.father',
            'childrenAsMother.media',
            'childrenAsFather.media',
        ]);

        $children = ($animal->gender === AnimalGender::Female ? $animal->childrenAsMother : $animal->childrenAsFather)
            ->filter(fn ($child) => $child->is_published && (! $child->published_at || $child->published_at->isPast()))
            ->sortBy(fn ($child) => [
                $child->status === AnimalStatus::Available ? 1 : ($child->status === AnimalStatus::Reserved ? 2 : ($child->status === AnimalStatus::Sold ? 3 : 4)),
                $child->sort_order,
            ]);

        $relatedAnimals = Animal::query()
            ->published()
            ->where('id', '!=', $animal->id)
            ->where(function ($q) use ($animal) {
                $q->where('breed', '=', $animal->breed)
                    ->orWhere('status', '=', AnimalStatus::Available);
            })
            ->with('media')
            ->orderedByStatus()
            ->take(3)
            ->get();

        return view('frontend.animals.show', [
            'animal' => $animal,
            'children' => $children,
            'relatedAnimals' => $relatedAnimals,
        ]);
    }
}
