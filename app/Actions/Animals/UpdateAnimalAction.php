<?php

namespace App\Actions\Animals;

use App\Models\Animal;
use App\Services\MediaService;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;

class UpdateAnimalAction
{
    protected MediaService $mediaService;

    public function __construct(MediaService $mediaService)
    {
        $this->mediaService = $mediaService;
    }

    public function handle(Animal $animal, array $data): Animal
    {
        return DB::transaction(function () use ($animal, $data) {
            /** @var Animal $locked */
            $locked = Animal::whereKey($animal->getKey())->lockForUpdate()->firstOrFail();

            $locked->update([
                'name' => $data['name'],
                'slug' => $data['slug'],
                'breed' => $data['breed'],
                'color' => $data['color'] ?? null,
                'gender' => $data['gender'],
                'status' => $data['status'],
                'type' => $data['type'],
                'date_of_birth' => $data['date_of_birth'] ?? null,
                'earliest_pickup_date' => $data['earliest_pickup_date'] ?? null,
                'description' => $data['description'] ?? null,
                'short_description' => $data['short_description'] ?? null,
                'mother_id' => $data['mother_id'] ?? null,
                'father_id' => $data['father_id'] ?? null,
                'is_featured' => $data['is_featured'],
                'is_published' => $data['is_published'],
                'published_at' => $data['published_at'] ?? null,
                'sort_order' => (function () use ($locked, $data) {
                    $newStatus = $data['status'] instanceof \App\Enums\AnimalStatus ? $data['status'] : \App\Enums\AnimalStatus::from($data['status']);
                    $sort = (isset($data['sort_order']) && (int) $data['sort_order'] > 0)
                        ? (int) $data['sort_order']
                        : ($locked->sort_order ?: Animal::nextSortOrderFor($data['status'], $data['breed'] ?? $locked->breed));

                    if ($newStatus === \App\Enums\AnimalStatus::Sold && $sort < 20) {
                        return Animal::nextSortOrderFor(\App\Enums\AnimalStatus::Sold);
                    }

                    return $sort;
                })(),
                'price' => $data['price'] ?? null,
                'meta_title' => $data['meta_title'] ?? null,
                'meta_description' => $data['meta_description'] ?? null,
            ]);

            if (isset($data['image']) && $data['image'] instanceof UploadedFile) {
                $this->mediaService->replace(
                    $locked,
                    $data['image'],
                    'animals'
                );
            }

            if (! empty($data['gallery']) && is_array($data['gallery'])) {
                foreach ($data['gallery'] as $galleryFile) {
                    if ($galleryFile instanceof UploadedFile) {
                        $this->mediaService->upload($locked, $galleryFile, 'animals');
                    }
                }
            }

            return $locked;
        });
    }
}
