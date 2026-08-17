<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\AnimalGender;
use App\Enums\AnimalStatus;
use App\Enums\AnimalType;
use Database\Factories\AnimalFactory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

/**
 * Represents an animal in the breeding program.
 *
 * This is the core domain entity. Uses "Animal" generically
 * instead of "Cat" to keep the architecture reusable.
 *
 * Responsibilities:
 * - Data representation and relationships
 * - Query scopes for filtering and searching
 * - Slug auto-generation
 * - Enum casting for type-safe status, gender, type
 *
 * Business logic belongs in Actions, not here.
 */
class Animal extends Model
{
    /** @use HasFactory<AnimalFactory> */
    use HasFactory;

    use HasUlids;
    use SoftDeletes;

    protected $fillable = [
        'name',
        'slug',
        'breed',
        'color',
        'gender',
        'status',
        'type',
        'date_of_birth',
        'description',
        'short_description',
        'mother_id',
        'father_id',
        'is_featured',
        'is_published',
        'published_at',
        'sort_order',
        'meta_title',
        'meta_description',
    ];

    protected function casts(): array
    {
        return [
            'gender' => AnimalGender::class,
            'status' => AnimalStatus::class,
            'type' => AnimalType::class,
            'date_of_birth' => 'date',
            'is_featured' => 'boolean',
            'is_published' => 'boolean',
            'published_at' => 'datetime',
        ];
    }

    // ─── Route Model Binding ────────────────────────────────────────

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    /**
     * Get professional status label for frontend badges.
     */
    public function statusLabel(): string
    {
        if ($this->status === AnimalStatus::Breeding) {
            if ($this->gender === AnimalGender::Female) {
                return 'Kotka Hodowlana';
            }
            if ($this->gender === AnimalGender::Male) {
                return 'Reproduktor';
            }
            return 'Kot Hodowlany';
        }

        return $this->status->label();
    }

    // ─── Relationships ──────────────────────────────────────────────

    /**
     * The animal's mother (pedigree).
     */
    public function mother(): BelongsTo
    {
        return $this->belongsTo(self::class, 'mother_id');
    }

    /**
     * The animal's father (pedigree).
     */
    public function father(): BelongsTo
    {
        return $this->belongsTo(self::class, 'father_id');
    }

    /**
     * Children where this animal is the mother.
     */
    public function childrenAsMother(): HasMany
    {
        return $this->hasMany(self::class, 'mother_id');
    }

    /**
     * Children where this animal is the father.
     */
    public function childrenAsFather(): HasMany
    {
        return $this->hasMany(self::class, 'father_id');
    }

    /**
     * Primary media (featured image) — matches Post pattern.
     */
    public function media(): MorphOne
    {
        return $this->morphOne(Media::class, 'mediable')
            ->orderByDesc('is_featured')
            ->orderBy('sort_order', 'asc');
    }

    /**
     * Gallery images — for the animal detail page.
     * Uses MorphMany for multiple images per animal.
     */
    public function gallery(): MorphMany
    {
        return $this->morphMany(Media::class, 'mediable')
            ->orderBy('sort_order', 'asc');
    }

    // ─── Query Scopes ───────────────────────────────────────────────

    /**
     * Only published animals.
     */
    public function scopePublished(Builder $query): Builder
    {
        return $query
            ->where('is_published', true)
            ->whereNotNull('published_at')
            ->whereIn('status', [AnimalStatus::Available, AnimalStatus::Reserved, AnimalStatus::Breeding]);
    }

    /**
     * Only animals marked as featured (for homepage).
     */
    public function scopeFeatured(Builder $query): Builder
    {
        return $query->where('is_featured', true);
    }

    /**
     * Only animals with a specific status.
     */
    public function scopeWithStatus(Builder $query, AnimalStatus $status): Builder
    {
        return $query->where('status', $status);
    }

    /**
     * Only available animals.
     */
    public function scopeAvailable(Builder $query): Builder
    {
        return $query->where('status', AnimalStatus::Available);
    }

    /**
     * Only breeding animals.
     */
    public function scopeBreeding(Builder $query): Builder
    {
        return $query->where('status', AnimalStatus::Breeding);
    }

    /**
     * Filter by gender.
     */
    public function scopeByGender(Builder $query, AnimalGender $gender): Builder
    {
        return $query->where('gender', $gender);
    }

    /**
     * Free-text search across name, breed, color, description.
     */
    public function scopeSearch(Builder $query, string $q): Builder
    {
        $q = trim($q);

        if ($q === '') {
            return $query;
        }

        return $query->where(function (Builder $sub) use ($q) {
            $sub->where('name', 'like', "%{$q}%")
                ->orWhere('breed', 'like', "%{$q}%")
                ->orWhere('color', 'like', "%{$q}%")
                ->orWhere('description', 'like', "%{$q}%");
        });
    }

    /**
     * Filter by status string value.
     */
    public function scopeStatusFilter(Builder $query, ?string $status): Builder
    {
        if (! $status) {
            return $query;
        }

        $enum = AnimalStatus::tryFrom($status);

        if (! $enum) {
            return $query;
        }

        return $query->where('status', $enum);
    }

    /**
     * Filter by gender string value.
     */
    public function scopeGenderFilter(Builder $query, ?string $gender): Builder
    {
        if (! $gender) {
            return $query;
        }

        $enum = AnimalGender::tryFrom($gender);

        if (! $enum) {
            return $query;
        }

        return $query->where('gender', $enum);
    }

    /**
     * Filter on soft deleted records.
     */
    public function scopeTrashedFilter(Builder $query, ?string $trashed): Builder
    {
        if (! $trashed) {
            return $query;
        }

        return match ($trashed) {
            'with' => $query->withTrashed(),
            'only' => $query->onlyTrashed(),
            default => $query,
        };
    }

    /**
     * Safe sorting on whitelisted columns.
     */
    public function scopeSortBySafe(Builder $query, string $sort, string $dir): Builder
    {
        $allowed = ['id', 'name', 'breed', 'status', 'gender', 'date_of_birth', 'created_at', 'sort_order'];

        if (! in_array($sort, $allowed, true)) {
            $sort = 'sort_order';
        }

        $dir = strtolower($dir) === 'asc' ? 'asc' : 'desc';

        return $query->orderBy($sort, $dir);
    }

    // ─── Auto Slug ──────────────────────────────────────────────────

    protected static function booted(): void
    {
        static::creating(function (Animal $animal) {
            if (blank($animal->slug) && filled($animal->name)) {
                $animal->slug = Str::slug($animal->name);
            }
        });

        static::updating(function (Animal $animal) {
            if ($animal->isDirty('name') && blank($animal->slug)) {
                $animal->slug = Str::slug($animal->name);
            }
        });
    }

    // ─── Computed Helpers ────────────────────────────────────────────

    /**
     * Calculate age from date_of_birth.
     * Returns null if date_of_birth is not set.
     */
    public function age(): ?string
    {
        if (! $this->date_of_birth) {
            return null;
        }

        $diff = $this->date_of_birth->diff(now());

        if ($diff->y >= 1) {
            return $diff->y === 1 ? '1 rok' : $diff->y.' lata';
        }

        if ($diff->m >= 1) {
            return $diff->m === 1 ? '1 miesiąc' : $diff->m.' miesięcy';
        }

        return $diff->d === 1 ? '1 dzień' : $diff->d.' dni';
    }
}
