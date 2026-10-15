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
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Carbon;
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

        return $this->status?->label() ?? 'Nieznany';
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

    /**
     * Blog posts that feature this animal.
     */
    public function posts(): BelongsToMany
    {
        return $this->belongsToMany(Post::class, 'animal_post')
            ->withPivot('sort_order');
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
            ->whereIn('status', [
                AnimalStatus::Available,
                AnimalStatus::Reserved,
                AnimalStatus::Breeding,
                AnimalStatus::Sold,
            ]);
    }

    /**
     * Order animals prioritizing Available cats first, then Reserved, Sold (W nowym domu), Breeding, and others.
     */
    public function scopeOrderedByStatus(Builder $query): Builder
    {
        return $query
            ->orderByRaw("CASE 
                WHEN status = 'available' THEN 1 
                WHEN status = 'reserved' THEN 2 
                WHEN status = 'sold' THEN 3 
                WHEN status = 'breeding' THEN 4 
                ELSE 5 
            END ASC")
            ->orderBy('sort_order', 'asc')
            ->latest('published_at');
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

    // ─── Computed Helpers & Pickup Rules ─────────────────────────────

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

    /**
     * Calculate age in complete weeks from date_of_birth.
     * Returns null if date_of_birth is missing.
     */
    public function ageInWeeks(?Carbon $targetDate = null): ?int
    {
        if (! $this->date_of_birth) {
            return null;
        }

        $target = $targetDate ? Carbon::instance($targetDate) : Carbon::now();

        if ($target->lt($this->date_of_birth)) {
            return 0;
        }

        return (int) $this->date_of_birth->diffInWeeks($target);
    }

    /**
     * Expected pickup window [start, end] corresponding to kitten reaching
     * 12 weeks (start) and 14 weeks (end) of age.
     *
     * @return array{start: Carbon, end: Carbon}|null
     */
    public function pickupWindow(): ?array
    {
        if (! $this->date_of_birth) {
            return null;
        }

        return [
            'start' => $this->date_of_birth->copy()->addWeeks(12),
            'end'   => $this->date_of_birth->copy()->addWeeks(14),
        ];
    }

    /**
     * Formatted string of the expected pickup window (e.g. "02.12.2026 – 16.12.2026").
     */
    public function pickupWindowFormatted(string $format = 'd.m.Y'): ?string
    {
        $window = $this->pickupWindow();

        if ($window === null) {
            return null;
        }

        return $window['start']->format($format) . ' – ' . $window['end']->format($format);
    }

    /**
     * Number of complete weeks remaining until reaching the minimum 12-week pickup age.
     * Returns 0 if the kitten is already 12 weeks or older.
     */
    public function weeksUntilPickupWindow(?Carbon $targetDate = null): ?int
    {
        if (! $this->date_of_birth) {
            return null;
        }

        $target = $targetDate ? Carbon::instance($targetDate) : Carbon::now();
        $pickupStart = $this->date_of_birth->copy()->addWeeks(12);

        if ($target->gte($pickupStart)) {
            return 0;
        }

        $weeks = (int) ceil($target->diffInDays($pickupStart) / 7);

        return max(1, $weeks);
    }

    /**
     * Whether this animal is an available kitten younger than 12 weeks (early reservation stage).
     */
    public function isEarlyReservation(?Carbon $targetDate = null): bool
    {
        if ($this->status !== AnimalStatus::Available) {
            return false;
        }

        $age = $this->ageInWeeks($targetDate);

        return $age !== null && $age < 12;
    }

    /**
     * Whether this animal is an available kitten currently within the 12–14-week pickup window.
     */
    public function isInPickupWindow(?Carbon $targetDate = null): bool
    {
        if ($this->status !== AnimalStatus::Available) {
            return false;
        }

        $age = $this->ageInWeeks($targetDate);

        return $age !== null && $age >= 12 && $age <= 14;
    }

    /**
     * Whether this animal is available and at or above pickup age (>= 12 weeks, or adult without DOB).
     */
    public function isReadyNow(?Carbon $targetDate = null): bool
    {
        if ($this->status !== AnimalStatus::Available) {
            return false;
        }

        $age = $this->ageInWeeks($targetDate);

        return $age === null || $age >= 12;
    }

    /**
     * Whether this animal is available and older than the initial 14-week pickup window.
     */
    public function isOdchowany(?Carbon $targetDate = null): bool
    {
        if ($this->status !== AnimalStatus::Available) {
            return false;
        }

        $age = $this->ageInWeeks($targetDate);

        return $age !== null && $age > 14;
    }

    /**
     * Unified, rule-compliant badge label for public cards, profiles, and admin tables.
     *
     * Rules:
     * - Non-available animals retain their domain status (e.g., Zarezerwowany, Kotka Hodowlana, W nowym domu).
     * - Available kittens < 12 weeks: "Wczesna rezerwacja • za X tyg."
     * - Available kittens 12–14 weeks: "Gotowy do odbioru (12–14 tyg.)"
     * - Available kittens > 14 weeks: "Gotowy do odbioru • Odchowany"
     * - Available without DOB: safe fallback "Dostępny do rezerwacji"
     */
    public function pickupBadgeLabel(?Carbon $targetDate = null): string
    {
        if ($this->status !== AnimalStatus::Available) {
            return $this->statusLabel();
        }

        if (! $this->date_of_birth) {
            return 'Dostępny do rezerwacji';
        }

        $age = $this->ageInWeeks($targetDate);

        if ($age === null) {
            return 'Dostępny do rezerwacji';
        }

        if ($age < 12) {
            $weeksUntil = $this->weeksUntilPickupWindow($targetDate);

            return "Wczesna rezerwacja • za {$weeksUntil} tyg.";
        }

        if ($age <= 14) {
            return 'Gotowy do odbioru (12–14 tyg.)';
        }

        return 'Gotowy do odbioru • Odchowany';
    }

    /**
     * Badge visual variant matching <x-frontend.badge variant="...">
     */
    public function pickupBadgeVariant(?Carbon $targetDate = null): string
    {
        if ($this->isEarlyReservation($targetDate)) {
            return 'warning';
        }

        return $this->status?->badgeVariant() ?? 'gold';
    }

    /**
     * Oblicza następny sugerowany numer sort_order według standardu hodowli:
     * - 1 - 19: Kocięta do rezerwacji / dostępne (Available / Reserved)
     * - 20 - 29: Koty w nowych domach (Sold / Alumni)
     * - 30 - 39: Dorosłe koty hodowlane rasy Kot Brytyjski (Breeding)
     * - 40 - 49: Dorosłe koty hodowlane rasy Kot Syjamski (Breeding)
     * - 50 - 59: Dorosłe koty hodowlane rasy Kot Bengalski (Breeding)
     */
    public static function nextSortOrderFor(string|AnimalStatus $status, ?string $breed = null): int
    {
        $statusValue = $status instanceof AnimalStatus ? $status->value : (string) $status;

        // 1. Koty w nowych domach (Sold): pula 20 - 29
        if ($statusValue === AnimalStatus::Sold->value) {
            $max = static::where('status', AnimalStatus::Sold)
                ->where('sort_order', '>=', 20)
                ->where('sort_order', '<', 30)
                ->max('sort_order');

            return $max !== null ? (int) $max + 1 : 20;
        }

        // 2. Dorosłe koty hodowlane (Breeding): pule rasowe 31–39, 41–49, 51–59
        if ($statusValue === AnimalStatus::Breeding->value) {
            $normalizedBreed = mb_strtolower(trim((string) $breed));

            $rangeStart = 31;
            if (str_contains($normalizedBreed, 'bryt') || str_contains($normalizedBreed, 'brit')) {
                $rangeStart = 31;
            } elseif (str_contains($normalizedBreed, 'syjam') || str_contains($normalizedBreed, 'siam')) {
                $rangeStart = 41;
            } elseif (str_contains($normalizedBreed, 'bengal')) {
                $rangeStart = 51;
            }

            $rangeEnd = $rangeStart + 8;

            $max = static::where('status', AnimalStatus::Breeding)
                ->whereBetween('sort_order', [$rangeStart, $rangeEnd])
                ->max('sort_order');

            return $max !== null ? (int) $max + 1 : $rangeStart;
        }

        // 3. Kocięta (Available / Reserved): pula 1 - 19
        $max = static::whereIn('status', [AnimalStatus::Available, AnimalStatus::Reserved])
            ->whereBetween('sort_order', [1, 19])
            ->max('sort_order');

        return $max !== null ? (int) $max + 1 : 1;
    }
}
