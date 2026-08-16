<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Media extends Model
{
    protected $table = 'media';

    protected $fillable = [
        'disk',
        'directory',
        'filename',
        'mime_type',
        'size',
        'title',
        'alt_text',
        'caption',
        'copyright',
        'sort_order',
        'is_featured',
        'mediable_type',
        'mediable_id',
    ];

    protected $casts = [
        'is_featured' => 'boolean',
        'sort_order' => 'integer',
        'size' => 'integer',
    ];

    public function mediable(): MorphTo
    {
        return $this->morphTo();
    }

    public function path(): string
    {
        if ($this->directory) {
            return $this->directory.'/'.$this->filename;
        }

        return $this->filename;
    }

    public function url(): string
    {
        if (str_starts_with($this->filename, 'http://') || str_starts_with($this->filename, 'https://')) {
            return $this->filename;
        }

        if ($this->directory) {
            return asset('storage/'.$this->directory.'/'.$this->filename);
        }

        return asset('storage/'.$this->filename);
    }

    public function isImage(): bool
    {
        if (! $this->mime_type) {
            return false;
        }

        return str_starts_with($this->mime_type, 'image/');
    }

    /* =========================================================================
       Backwards Compatibility Attribute Aliases
       ========================================================================= */

    protected function fileName(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->attributes['filename'] ?? null,
            set: fn ($value) => ['filename' => $value],
        );
    }

    protected function filePath(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->path(),
            set: function ($value) {
                $parts = explode('/', str_replace('\\', '/', $value));
                $filename = array_pop($parts);
                $directory = empty($parts) ? null : implode('/', $parts);

                return [
                    'filename' => $filename,
                    'directory' => $directory,
                ];
            },
        );
    }

    protected function fileSize(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->attributes['size'] ?? null,
            set: fn ($value) => ['size' => (int) $value],
        );
    }

    /* =========================================================================
       Scopes for CMS Filtering & Sorting
       ========================================================================= */

    public function scopeSearch(Builder $query, ?string $search): Builder
    {
        if (! $search) {
            return $query;
        }

        return $query->where(function (Builder $q) use ($search) {
            $q->where('filename', 'like', "%{$search}%")
                ->orWhere('title', 'like', "%{$search}%")
                ->orWhere('alt_text', 'like', "%{$search}%")
                ->orWhere('caption', 'like', "%{$search}%")
                ->orWhere('copyright', 'like', "%{$search}%")
                ->orWhere('directory', 'like', "%{$search}%");
        });
    }

    public function scopeTypeFilter(Builder $query, ?string $type): Builder
    {
        if (! $type) {
            return $query;
        }

        if ($type === 'unattached') {
            return $query->whereNull('mediable_type');
        }

        if ($type === 'post') {
            return $query->where('mediable_type', \App\Models\Post::class);
        }

        if ($type === 'user') {
            return $query->where('mediable_type', \App\Models\User::class);
        }

        if ($type === 'animal') {
            return $query->where('mediable_type', \App\Models\Animal::class);
        }

        return $query;
    }

    public function scopeFeaturedFilter(Builder $query, ?string $featured): Builder
    {
        if ($featured === 'yes') {
            return $query->where('is_featured', true);
        }

        if ($featured === 'no') {
            return $query->where('is_featured', false);
        }

        return $query;
    }

    public function scopeTrashedFilter(Builder $query, ?string $trashed): Builder
    {
        return $query;
    }

    public function scopeSortBySafe(Builder $query, ?string $col, ?string $dir): Builder
    {
        $dir = strtolower((string) $dir) === 'asc' ? 'asc' : 'desc';

        $allowed = [
            'id' => 'id',
            'file_name' => 'filename',
            'filename' => 'filename',
            'size' => 'size',
            'file_size' => 'size',
            'created_at' => 'created_at',
            'sort_order' => 'sort_order',
        ];

        $column = $allowed[$col] ?? 'created_at';

        return $query->orderBy($column, $dir);
    }
}

