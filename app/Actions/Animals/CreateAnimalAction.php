<?php

namespace App\Actions\Animals;

use App\Models\Animal;
use App\Services\MediaService;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;

class CreateAnimalAction
{
    protected MediaService $mediaService;

    public function __construct(MediaService $mediaService)
    {
        $this->mediaService = $mediaService;
    }

    public function handle(array $data): Animal
    {
        return DB::transaction(function () use ($data) {
            $animal = Animal::create([
                'name' => $data['name'],
                'slug' => $data['slug'],
                'breed' => $data['breed'],
                'color' => $data['color'] ?? null,
                'gender' => $data['gender'],
                'status' => $data['status'],
                'type' => $data['type'],
                'date_of_birth' => $data['date_of_birth'] ?? null,
                'description' => $data['description'] ?? null,
                'short_description' => $data['short_description'] ?? null,
                'mother_id' => $data['mother_id'] ?? null,
                'father_id' => $data['father_id'] ?? null,
                'is_featured' => $data['is_featured'],
                'is_published' => $data['is_published'],
                'published_at' => $data['published_at'] ?? null,
                'sort_order' => $data['sort_order'] ?? 0,
                'meta_title' => $data['meta_title'] ?? null,
                'meta_description' => $data['meta_description'] ?? null,
            ]);

            if (isset($data['image']) && $data['image'] instanceof UploadedFile) {
                $this->mediaService->upload(
                    $animal,
                    $data['image'],
                    'animals'
                );
            }

            if (!empty($data['gallery']) && is_array($data['gallery'])) {
                foreach ($data['gallery'] as $galleryFile) {
                    if ($galleryFile instanceof UploadedFile) {
                        $this->mediaService->upload($animal, $galleryFile, 'animals');
                    }
                }
            }

            return $animal;
        });
    }
}
