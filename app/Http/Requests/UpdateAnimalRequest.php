<?php

namespace App\Http\Requests;

use App\Enums\AnimalGender;
use App\Enums\AnimalStatus;
use App\Enums\AnimalType;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class UpdateAnimalRequest extends FormRequest
{
    public function authorize(): bool
    {
        $animal = $this->route('animal');

        return $animal ? ($this->user()?->can('update', $animal) ?? false) : false;
    }

    protected function prepareForValidation(): void
    {
        $name = trim((string) $this->input('name', ''));
        $slug = trim((string) $this->input('slug', ''));
        $isPublished = $this->boolean('is_published');
        $isFeatured = $this->boolean('is_featured');
        $publishedAt = $this->input('published_at');

        $cropped = $this->input('cropped_image');
        if (is_string($cropped) && str_starts_with($cropped, 'data:image/')) {
            $dataPos = strpos($cropped, ',');
            if ($dataPos !== false) {
                $base64Data = substr($cropped, $dataPos + 1);
                $decoded = base64_decode($base64Data);
                if ($decoded !== false) {
                    $tmpFilePath = tempnam(sys_get_temp_dir(), 'crop_img_');
                    file_put_contents($tmpFilePath, $decoded);

                    preg_match('/^data:(image\/[a-zA-Z0-9\+\-]+);base64/', $cropped, $matches);
                    $mimeType = $matches[1] ?? 'image/jpeg';
                    $ext = match ($mimeType) {
                        'image/png' => 'png',
                        'image/webp' => 'webp',
                        default => 'jpg',
                    };

                    $file = new \Illuminate\Http\UploadedFile(
                        $tmpFilePath,
                        'cropped_'.uniqid().'.'.$ext,
                        $mimeType,
                        null,
                        true
                    );

                    $this->files->set('image', $file);
                }
            }
        }

        $this->merge([
            'name' => $name,
            'slug' => $slug !== '' ? Str::slug($slug) : Str::slug($name),
            'is_published' => $isPublished,
            'is_featured' => $isFeatured,
            'published_at' => $isPublished ? ($publishedAt ?: now()) : null,
        ]);
    }

    public function rules(): array
    {
        $animal = $this->route('animal');

        return [
            'name' => ['required', 'string', 'max:255'],
            'slug' => ['required', 'string', 'max:255', Rule::unique('animals', 'slug')->ignore($animal)],
            'breed' => ['required', 'string', 'max:255'],
            'color' => ['nullable', 'string', 'max:255'],
            'gender' => ['required', Rule::enum(AnimalGender::class)],
            'status' => ['required', Rule::enum(AnimalStatus::class)],
            'type' => ['required', Rule::enum(AnimalType::class)],
            'date_of_birth' => ['nullable', 'date'],
            'earliest_pickup_date' => ['nullable', 'date'],
            'description' => ['nullable', 'string'],
            'short_description' => ['nullable', 'string', 'max:500'],

            'mother_id' => ['nullable', 'string', Rule::exists('animals', 'id')],
            'father_id' => ['nullable', 'string', Rule::exists('animals', 'id')],

            'is_featured' => ['required', 'boolean'],
            'is_published' => ['required', 'boolean'],
            'published_at' => ['nullable', 'date'],
            'sort_order' => ['nullable', 'integer'],
            'price' => ['nullable', 'numeric', 'min:0'],

            'meta_title' => ['nullable', 'string', 'max:255'],
            'meta_description' => ['nullable', 'string', 'max:255'],

            'cropped_image' => ['nullable', 'string'],
            'image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp,jfif', 'max:20480'],
            'gallery' => ['nullable', 'array', 'max:50'],
            'gallery.*' => ['image', 'mimes:jpg,jpeg,png,webp,jfif', 'max:20480'],
        ];
    }
}
