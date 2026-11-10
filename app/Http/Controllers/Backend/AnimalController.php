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
use App\Models\Media;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
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

    public function updateQuickPrice(\Illuminate\Http\Request $request, Animal $animal)
    {
        $this->authorize('update', $animal);

        $validated = $request->validate([
            'price' => ['nullable', 'string', 'max:50'],
        ]);

        \Illuminate\Support\Facades\DB::transaction(function () use ($animal, $validated) {
            /** @var Animal $locked */
            $locked = Animal::whereKey($animal->getKey())->lockForUpdate()->firstOrFail();
            $locked->price = $validated['price'] ?? null;
            $locked->save();
        });

        $animal->refresh();

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'price' => $animal->price,
                'formatted' => $animal->formattedPrice(),
            ]);
        }

        $formatted = $animal->formattedPrice() ?: 'usunięta (brak ceny)';

        return back()->with('success', "Cena kota '{$animal->name}' została zaktualizowana: {$formatted}.");
    }

    public function storeGalleryMedia(Request $request, Animal $animal, \App\Services\MediaService $mediaService): RedirectResponse
    {
        $this->authorize('update', $animal);

        $validated = $request->validate([
            'gallery' => ['required', 'array', 'min:1', 'max:50'],
            'gallery.*' => ['required', 'image', 'mimes:jpg,jpeg,png,webp,jfif', 'max:20480'],
        ]);

        DB::transaction(function () use ($animal, $validated, $mediaService) {
            foreach ($validated['gallery'] as $galleryFile) {
                $mediaService->upload($animal, $galleryFile, 'animals');
            }
        });

        $count = count($validated['gallery']);

        return back()->with('success', "Dodano {$count} zdjęć do galerii kota '{$animal->name}'.");
    }

    public function setFeaturedMedia(Animal $animal, Media $media): RedirectResponse
    {
        $this->authorize('update', $animal);

        if ($media->mediable_type !== Animal::class || (string) $media->mediable_id !== (string) $animal->id) {
            abort(404, 'To zdjęcie nie należy do tego kota.');
        }

        DB::transaction(function () use ($animal, $media) {
            Media::query()
                ->where('mediable_type', Animal::class)
                ->where('mediable_id', (string) $animal->id)
                ->update(['is_featured' => false]);

            $media->update(['is_featured' => true]);
        });

        return back()->with('success', "Zdjęcie zostało ustawione jako zdjęcie główne kota '{$animal->name}'.");
    }

    public function reorderGalleryMedia(Request $request, Animal $animal): JsonResponse
    {
        $this->authorize('update', $animal);

        $validated = $request->validate([
            'media_ids' => ['required', 'array', 'min:1'],
            'media_ids.*' => ['required', 'integer', 'exists:media,id'],
        ]);

        DB::transaction(function () use ($animal, $validated) {
            foreach ($validated['media_ids'] as $index => $mediaId) {
                Media::query()
                    ->where('id', $mediaId)
                    ->where('mediable_type', Animal::class)
                    ->where('mediable_id', (string) $animal->id)
                    ->update(['sort_order' => $index + 1]);
            }
        });

        return response()->json([
            'success' => true,
            'message' => "Kolejność zdjęć w galerii kota '{$animal->name}' została pomyślnie zaktualizowana.",
        ]);
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
            Log::error('Animal delete failed', ['animal_id' => $animal->id, 'exception_class' => get_class($e), 'message' => $e->getMessage()]);

            return back()
                ->with('error', 'Animal could not be deleted.');
        }
    }

    public function restore(string $id)
    {
        $animal = Animal::withTrashed()->findOrFail($id);

        $this->authorize('restore', $animal);

        try {
            $animal->restore();

            return redirect()
                ->route('backend.animals.index')
                ->with('success', "Animal '{$animal->name}' restored successfully.");
        } catch (Throwable $e) {
            Log::error('Animal restore failed', ['animal_id' => $id, 'exception_class' => get_class($e), 'message' => $e->getMessage()]);

            return back()
                ->with('error', 'Animal could not be restored.');
        }
    }

    public function forceDelete(string $id)
    {
        $animal = Animal::withTrashed()->findOrFail($id);

        $this->authorize('forceDelete', $animal);

        try {
            $name = $animal->name;

            $animal->forceDelete();

            return redirect()
                ->route('backend.animals.index')
                ->with('success', "Animal '{$name}' permanently deleted.");
        } catch (Throwable $e) {
            Log::error('Animal forceDelete failed', ['animal_id' => $id, 'exception_class' => get_class($e), 'message' => $e->getMessage()]);

            return back()
                ->with('error', 'Animal could not be permanently deleted.');
        }
    }
}
