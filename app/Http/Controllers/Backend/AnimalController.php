<?php

namespace App\Http\Controllers\Backend;

use App\Actions\Animals\CreateAnimalAction;
use App\Actions\Animals\UpdateAnimalAction;
use App\Enums\AnimalGender;
use App\Enums\AnimalStatus;
use App\Enums\AnimalType;
use App\Http\Controllers\Controller;
use App\Http\Requests\AnimalIndexRequest;
use App\Http\Requests\StoreAnimalRequest;
use App\Http\Requests\UpdateAnimalRequest;
use App\Models\Animal;
use Throwable;

class AnimalController extends Controller
{
    protected CreateAnimalAction $createAnimalAction;

    protected UpdateAnimalAction $updateAnimalAction;

    public function __construct(CreateAnimalAction $createAnimalAction, UpdateAnimalAction $updateAnimalAction)
    {
        $this->createAnimalAction = $createAnimalAction;
        $this->updateAnimalAction = $updateAnimalAction;
    }

    public function index(AnimalIndexRequest $request)
    {
        $this->authorize('viewAny', Animal::class);

        $filters = $request->defaults();

        $animals = Animal::query()
            ->with(['media'])
            ->search($filters['q'])
            ->statusFilter($filters['status'])
            ->genderFilter($filters['gender'])
            ->trashedFilter($filters['trashed'])
            ->sortBySafe($filters['sort'], $filters['dir'])
            ->paginate($filters['per_page'])
            ->withQueryString();

        return view('backend.animals.index', [
            'animals' => $animals,
            'filters' => $filters,
            'perPageAllowed' => [10, 25, 50, 100],
            'statuses' => AnimalStatus::cases(),
            'genders' => AnimalGender::cases(),
        ]);
    }

    public function create()
    {
        $this->authorize('create', Animal::class);

        $potentialParents = Animal::query()->orderBy('name')->get(['id', 'name', 'gender']);

        return view('backend.animals.create', [
            'potentialParents' => $potentialParents,
            'statuses' => AnimalStatus::cases(),
            'genders' => AnimalGender::cases(),
            'types' => AnimalType::cases(),
        ]);
    }

    public function store(StoreAnimalRequest $request)
    {
        $this->authorize('create', Animal::class);

        $animal = $this->createAnimalAction->handle($request->validated());

        return redirect()
            ->route('backend.animals.index')
            ->with('success', "Animal '{$animal->name}' created successfully.");
    }

    public function show(Animal $animal)
    {
        $this->authorize('view', $animal);

        $animal->load(['media', 'gallery', 'mother', 'father', 'childrenAsMother', 'childrenAsFather']);

        return view('backend.animals.show', [
            'animal' => $animal,
        ]);
    }

    public function edit(Animal $animal)
    {
        $this->authorize('update', $animal);

        $animal->load(['media', 'gallery']);

        $potentialParents = Animal::query()
            ->where('id', '!=', $animal->id)
            ->orderBy('name')
            ->get(['id', 'name', 'gender']);

        return view('backend.animals.edit', [
            'animal' => $animal,
            'potentialParents' => $potentialParents,
            'statuses' => AnimalStatus::cases(),
            'genders' => AnimalGender::cases(),
            'types' => AnimalType::cases(),
        ]);
    }

    public function update(UpdateAnimalRequest $request, Animal $animal)
    {
        $this->authorize('update', $animal);

        $animal = $this->updateAnimalAction->handle($animal, $request->validated());

        return redirect()
            ->route('backend.animals.edit', $animal)
            ->with('success', "Animal '{$animal->name}' updated successfully.");
    }

    public function destroy(Animal $animal)
    {
        $this->authorize('delete', $animal);

        try {
            $animal->delete();

            return redirect()
                ->route('backend.animals.index')
                ->with('success', "Animal '{$animal->name}' deleted successfully.");
        } catch (Throwable $e) {
            return back()
                ->with('error', 'Animal could not be deleted.');
        }
    }

    public function restore(string $id)
    {
        try {
            $animal = Animal::withTrashed()->findOrFail($id);

            $this->authorize('restore', $animal);

            $animal->restore();

            return redirect()
                ->route('backend.animals.index')
                ->with('success', "Animal '{$animal->name}' restored successfully.");
        } catch (Throwable $e) {
            return back()
                ->with('error', 'Animal could not be restored.');
        }
    }

    public function forceDelete(string $id)
    {
        try {
            $animal = Animal::withTrashed()->findOrFail($id);

            $this->authorize('forceDelete', $animal);

            $name = $animal->name;

            $animal->forceDelete();

            return redirect()
                ->route('backend.animals.index')
                ->with('success', "Animal '{$name}' permanently deleted.");
        } catch (Throwable $e) {
            return back()
                ->with('error', 'Animal could not be permanently deleted.');
        }
    }
}
