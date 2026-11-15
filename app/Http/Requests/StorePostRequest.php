<?php

namespace App\Http\Requests;

use App\Models\Post;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class StorePostRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->can('create', \App\Models\Post::class) ?? false;
    }

    /**
     * Input normaliseren vóór validatie.
     *
     * - title trimmen
     * - slug automatisch genereren als die leeg is
     * - categories altijd als array doorgeven
     * - published_at automatisch invullen als post gepubliceerd is
     * - sections_json parsen naar PHP array
     * - body automatisch opbouwen uit sections (backward compat)
     * - featured_animal_ids doorgeven voor pivot sync
     */
    protected function prepareForValidation(): void
    {
        $title = trim((string) $this->input('title', ''));
        $slug = trim((string) $this->input('slug', ''));
        $isPublished = $this->boolean('is_published');
        $publishedAt = $this->input('published_at');
        if ($publishedAt !== null && trim((string) $publishedAt) === '') {
            $publishedAt = null;
        }

        // Base64 Cropped Featured Image handler
        $cropped = $this->input('cropped_image');
        if (is_string($cropped) && str_starts_with($cropped, 'data:image/')) {
            $dataPos = strpos($cropped, ',');
            if ($dataPos !== false) {
                $base64Data = substr($cropped, $dataPos + 1);
                $decoded = base64_decode($base64Data, true);
                if ($decoded !== false) {
                    $tmpFilePath = tempnam(sys_get_temp_dir(), 'crop_post_');
                    if ($tmpFilePath !== false) {
                        file_put_contents($tmpFilePath, $decoded);
                        register_shutdown_function(static function () use ($tmpFilePath): void {
                            @unlink($tmpFilePath);
                        });

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
        }

        // Parse sections JSON sent from the structured editor
        $sectionsRaw = $this->input('sections_json', '');
        $sections = null;
        if (is_string($sectionsRaw) && $sectionsRaw !== '') {
            $decoded = json_decode($sectionsRaw, true);
            $sections = is_array($decoded) ? $decoded : null;
        } elseif (is_array($this->input('sections'))) {
            $sections = $this->input('sections');
        }

        // Base64 drag & drop / in-place crop handler for section images
        if (is_array($sections)) {
            $mediaService = app(\App\Services\MediaService::class);
            foreach ($sections as &$sec) {
                foreach (['image_url', 'image_url_2'] as $slotKey) {
                    $val = $sec[$slotKey] ?? null;
                    if (is_string($val) && str_starts_with($val, 'data:image/')) {
                        $dataPos = strpos($val, ',');
                        if ($dataPos !== false) {
                            $decoded = base64_decode(substr($val, $dataPos + 1), true);
                            if ($decoded !== false) {
                                $tmpPath = tempnam(sys_get_temp_dir(), 'sec_img_');
                                if ($tmpPath !== false) {
                                    file_put_contents($tmpPath, $decoded);
                                    register_shutdown_function(static function () use ($tmpPath): void {
                                        @unlink($tmpPath);
                                    });

                                    preg_match('/^data:(image\/[a-zA-Z0-9\+\-]+);base64/', $val, $m);
                                    $mime = $m[1] ?? 'image/jpeg';
                                    $ext = match ($mime) {
                                        'image/png' => 'png',
                                        'image/webp' => 'webp',
                                        default => 'jpg',
                                    };

                                    $uploaded = new \Illuminate\Http\UploadedFile(
                                        $tmpPath,
                                        'section_'.uniqid().'.'.$ext,
                                        $mime,
                                        null,
                                        true
                                    );

                                    $media = $mediaService->upload(null, $uploaded, 'posts');
                                    $sec[$slotKey] = $media->url();
                                    @unlink($tmpPath);
                                }
                            }
                        }
                    }
                }
            }
            unset($sec);
        }

        // Regenerate body from sections for backward compat (RSS, search index)
        $body = $this->input('body', '');
        if ($sections) {
            $body = Post::sectionsToMarkdown($sections);
        }

        $this->merge([
            'title' => $title,
            'slug' => $slug !== '' ? Str::slug($slug) : Str::slug($title),
            'hero_layout' => $this->input('hero_layout', 'auto') ?: 'auto',
            'cropped_image' => $this->input('cropped_image'),
            'is_published' => $isPublished,
            'categories' => $this->input('categories', []),
            'published_at' => $publishedAt ?: ($isPublished ? now() : null),
            'sections' => $sections,
            'body' => $body,
            'featured_animal_ids' => $this->input('featured_animal_ids', []),
            'featured_media_id' => $this->input('featured_media_id') ?: null,
            'remove_featured_media' => $this->boolean('remove_featured_media'),
        ]);
    }

    public function rules(): array
    {
        return [
            'user_id' => ['nullable', 'integer', Rule::exists('users', 'id')],
            'title' => ['required', 'string', 'min:3', 'max:255'],
            'slug' => ['required', 'string', 'min:3', 'max:255', Rule::unique('posts', 'slug')],
            'hero_layout' => ['nullable', 'string', 'in:auto,split,magazine,wide'],
            'cropped_image' => ['nullable', 'string'],
            'excerpt' => ['nullable', 'string', 'max:1000'],
            'body' => ['nullable', 'string'],
            'sections' => ['nullable', 'array'],
            'sections.*.heading' => ['nullable', 'string'],
            'sections.*.body' => ['nullable', 'string'],
            'sections.*.tip' => ['nullable', 'string'],
            'sections.*.layout' => ['nullable', 'string', 'in:auto,duo,split,full,cat_card'],
            'sections.*.image_url' => ['nullable', 'string'],
            'sections.*.image_url_2' => ['nullable', 'string'],
            'sections.*.image_caption' => ['nullable', 'string', 'max:255'],
            'sections.*.image_orientation' => ['nullable', 'string', 'in:portrait,landscape,square'],
            'sections.*.image_focal_point' => ['nullable', 'string', 'max:50'],
            'sections.*.image_prompt' => ['nullable', 'string'],
            'sections_json' => ['nullable', 'string'],
            'is_published' => ['required', 'boolean'],
            'published_at' => ['nullable', 'date'],
            'categories' => ['nullable', 'array'],
            'categories.*' => ['integer', Rule::exists('categories', 'id')],
            'image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:4096'],
            'featured_media_id' => ['nullable', 'integer', Rule::exists('media', 'id')],
            'remove_featured_media' => ['nullable', 'boolean'],
            'featured_animal_ids' => ['nullable', 'array'],
            'featured_animal_ids.*' => ['string', Rule::exists('animals', 'id')],
        ];
    }
}
