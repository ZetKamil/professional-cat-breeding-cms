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
        'earliest_pickup_date',
        'description',
        'short_description',
        'mother_id',
        'father_id',
        'is_featured',
        'is_published',
        'published_at',
        'sort_order',
        'price',
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
            'earliest_pickup_date' => 'date',
            'price' => 'integer',
            'is_featured' => 'boolean',
            'is_published' => 'boolean',
            'published_at' => 'datetime',
        ];
    }

    /**
     * Mutator to sanitize price input (e.g. '2 700 zł' -> 2700).
     * Hardened against negative numbers, zero, extreme values, and weird characters.
     */
    public function setPriceAttribute($value): void
    {
        if ($value === null || $value === '') {
            $this->attributes['price'] = null;

            return;
        }

        if (is_numeric($value)) {
            $num = (float) $value;
            if ($num <= 0) {
                $this->attributes['price'] = null;

                return;
            }

            if ($num >= 1000000) {
                $this->attributes['price'] = 1000000;

                return;
            }

            $this->attributes['price'] = (int) round($num);

            return;
        }

        if (is_string($value)) {
            // Reject negative sign
            if (str_contains($value, '-')) {
                $this->attributes['price'] = null;

                return;
            }

            $value = trim($value);
            // If decimal fraction exists like ,00 or .00 or ,50, strip fractional part
            $value = preg_replace('/[,.]\d{1,2}\s*(?:zł|pln)?$/iu', '', $value);

            $clean = preg_replace('/[^\d]/', '', $value);
            if ($clean === '') {
                $this->attributes['price'] = null;

                return;
            }

            // Guard against massive integer overflow in PHP before int cast
            if (strlen($clean) > 7 || (float) $clean > 1000000) {
                $this->attributes['price'] = 1000000;

                return;
            }

            $price = (int) $clean;
            $this->attributes['price'] = $price > 0 ? $price : null;

            return;
        }

        $this->attributes['price'] = null;
    }

    /**
     * Formatted price for luxury frontend and admin display (e.g. '2 700 zł').
     */
    public function formattedPrice(): ?string
    {
        if ($this->price === null || $this->price <= 0) {
            return null;
        }

        return number_format((float) $this->price, 0, ',', ' ').' zł';
    }

    /**
     * Determine if price badge should be displayed.
     * Only displayed for available/reservable kittens with a positive price,
     * hidden for adult breeding cats/reproduktors, sold, or retired cats.
     */
    public function shouldShowPrice(): bool
    {
        return $this->price !== null
            && $this->price > 0
            && $this->status !== AnimalStatus::Breeding
            && in_array($this->status, [AnimalStatus::Available, AnimalStatus::Reserved], true);
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
            ->where(function (Builder $q) {
                $q->whereNull('published_at')
                    ->orWhere('published_at', '<=', now());
            })
            ->whereIn('status', [
                AnimalStatus::Available,
                AnimalStatus::Reserved,
                AnimalStatus::Breeding,
                AnimalStatus::Sold,
                AnimalStatus::Retired,
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
     * Handles future dates, zero days, and Polish pluralization rules.
     */
    public function age(): ?string
    {
        if (! $this->date_of_birth) {
            return null;
        }

        if ($this->date_of_birth->isFuture()) {
            return 'Oczekiwany miot';
        }

        $diff = $this->date_of_birth->diff(now());

        if ($diff->y >= 1) {
            if ($diff->y === 1) {
                return '1 rok';
            }
            $mod10 = $diff->y % 10;
            $mod100 = $diff->y % 100;
            if ($mod10 >= 2 && $mod10 <= 4 && ($mod100 < 10 || $mod100 >= 20)) {
                return $diff->y.' lata';
            }

            return $diff->y.' lat';
        }

        if ($diff->m >= 1) {
            if ($diff->m === 1) {
                return '1 miesiąc';
            }
            if ($diff->m >= 2 && $diff->m <= 4) {
                return $diff->m.' miesiące';
            }

            return $diff->m.' miesięcy';
        }

        if ($diff->d === 0) {
            return 'Dziś';
        }

        return $diff->d === 1 ? '1 dzień' : $diff->d.' dni';
    }

    /**
     * Calculate age in complete weeks from date_of_birth.
     * Returns null if date_of_birth is missing.
     */
    public function ageInWeeks(?\Carbon\CarbonInterface $targetDate = null): ?int
    {
        if (! $this->date_of_birth) {
            return null;
        }

        $target = $targetDate ? Carbon::parse($targetDate) : Carbon::now();
        $dob = Carbon::parse($this->date_of_birth);

        if ($target->lt($dob)) {
            return 0;
        }

        return (int) $dob->diffInWeeks($target);
    }

    /**
     * Earliest possible date the animal can be picked up by its new family.
     * Uses explicit earliest_pickup_date override if provided by breeder,
     * otherwise falls back to standard felinology math (date_of_birth + 12 weeks).
     */
    public function earliestPickupDate(): ?Carbon
    {
        if ($this->earliest_pickup_date) {
            return Carbon::parse($this->earliest_pickup_date);
        }

        if ($this->date_of_birth) {
            return Carbon::parse($this->date_of_birth)->addWeeks(12);
        }

        return null;
    }

    /**
     * Expected pickup window [start, end] corresponding to kitten reaching
     * pickup readiness (start) and standard 2-week collection window (end).
     *
     * @return array{start: Carbon, end: Carbon}|null
     */
    public function pickupWindow(): ?array
    {
        $start = $this->earliestPickupDate();

        if (! $start) {
            return null;
        }

        return [
            'start' => $start,
            'end' => $start->copy()->addWeeks(2),
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

        return $window['start']->format($format).' – '.$window['end']->format($format);
    }

    /**
     * Number of complete weeks remaining until reaching the minimum pickup date.
     * Returns 0 if the kitten has already reached or passed the pickup date.
     */
    public function weeksUntilPickupWindow(?\Carbon\CarbonInterface $targetDate = null): ?int
    {
        $pickupStart = $this->earliestPickupDate();

        if (! $pickupStart) {
            return null;
        }

        $target = $targetDate ? Carbon::parse($targetDate) : Carbon::now();

        if ($target->gte($pickupStart)) {
            return 0;
        }

        $weeks = (int) ceil($target->diffInDays($pickupStart) / 7);

        return max(1, $weeks);
    }

    /**
     * Whether this animal is an available kitten in the early reservation stage (before pickup date).
     */
    public function isEarlyReservation(?\Carbon\CarbonInterface $targetDate = null): bool
    {
        if ($this->status !== AnimalStatus::Available) {
            return false;
        }

        $pickupStart = $this->earliestPickupDate();

        if ($pickupStart !== null) {
            $target = $targetDate ? Carbon::parse($targetDate) : Carbon::now();

            return $target->lt($pickupStart);
        }

        $age = $this->ageInWeeks($targetDate);

        return $age !== null && $age < 12;
    }

    /**
     * Whether this animal is an available kitten currently within the pickup window (start to start + 2 weeks).
     */
    public function isInPickupWindow(?\Carbon\CarbonInterface $targetDate = null): bool
    {
        if ($this->status !== AnimalStatus::Available) {
            return false;
        }

        $window = $this->pickupWindow();

        if (! $window) {
            return false;
        }

        $target = $targetDate ? Carbon::parse($targetDate) : Carbon::now();

        return $target->gte($window['start']->copy()->startOfDay())
            && $target->lte($window['end']->copy()->endOfDay());
    }

    /**
     * Whether this animal is available and at or above pickup age/date (or adult without DOB/pickup date).
     */
    public function isReadyNow(?\Carbon\CarbonInterface $targetDate = null): bool
    {
        if ($this->status !== AnimalStatus::Available) {
            return false;
        }

        $pickupStart = $this->earliestPickupDate();

        if ($pickupStart !== null) {
            $target = $targetDate ? Carbon::parse($targetDate) : Carbon::now();

            return $target->gte($pickupStart->copy()->startOfDay());
        }

        $age = $this->ageInWeeks($targetDate);

        return $age === null || $age >= 12;
    }

    /**
     * Whether this animal is available and older than the initial pickup window.
     */
    public function isOdchowany(?\Carbon\CarbonInterface $targetDate = null): bool
    {
        if ($this->status !== AnimalStatus::Available) {
            return false;
        }

        if ($this->isEarlyReservation($targetDate)) {
            return false;
        }

        if ($this->isInPickupWindow($targetDate)) {
            return false;
        }

        $window = $this->pickupWindow();

        if ($window !== null) {
            $target = $targetDate ? Carbon::parse($targetDate) : Carbon::now();

            return $target->gt($window['end']->copy()->endOfDay());
        }

        $age = $this->ageInWeeks($targetDate);

        return $age !== null && $age > 14;
    }

    /**
     * Unified, rule-compliant status badge label for public cards, profiles, and admin tables.
     *
     * Rules:
     * - Non-available animals retain their domain status (e.g., Zarezerwowany, Kotka Hodowlana, W nowym domu).
     * - Available kittens before pickup date: "Dostępny do rezerwacji"
     * - Available kittens at or past pickup date: "Gotowy do odbioru"
     * - Available without DOB and without pickup date: safe fallback "Dostępny do rezerwacji"
     */
    public function pickupBadgeLabel(?\Carbon\CarbonInterface $targetDate = null): string
    {
        if ($this->status !== AnimalStatus::Available) {
            return $this->statusLabel();
        }

        $pickupStart = $this->earliestPickupDate();

        if ($pickupStart === null && ! $this->date_of_birth) {
            return 'Dostępny do rezerwacji';
        }

        if ($this->isEarlyReservation($targetDate)) {
            return 'Dostępny do rezerwacji';
        }

        return 'Gotowy do odbioru';
    }

    /**
     * Human-friendly timeline label for pickup readiness.
     *
     * Rules:
     * - Only available animals with a valid pickup date / birth date have a timeline countdown.
     * - Kitten < 12 weeks (or before earliest pickup date): "Odbiór: za ok. X tyg."
     * - Cat in 12–14 weeks window (or within initial 2-week pickup window): "Gotowy do odbioru od zaraz"
     * - Cat older than 14 weeks (confirmed ready): "Gotowy do odbioru — termin do ustalenia"
     * - Non-available or missing DOB/pickup date: null
     */
    public function pickupTimelineLabel(?\Carbon\CarbonInterface $targetDate = null): ?string
    {
        if ($this->status !== AnimalStatus::Available) {
            return null;
        }

        $pickupStart = $this->earliestPickupDate();

        if (! $pickupStart) {
            return null;
        }

        if ($this->isEarlyReservation($targetDate)) {
            $weeksUntil = $this->weeksUntilPickupWindow($targetDate);

            return "Odbiór: za ok. {$weeksUntil} tyg.";
        }

        if ($this->isOdchowany($targetDate)) {
            return 'Gotowy do odbioru — termin do ustalenia';
        }

        return 'Gotowy do odbioru od zaraz';
    }

    /**
     * Compact timeline label specifically optimized for animal cards to eliminate
     * redundancy with the status badge overlaid on the card photo.
     *
     * Example:
     * - Photo badge: "Gotowy do odbioru"
     * - Card timeline: "Odbiór: termin do ustalenia" (instead of repeating "Gotowy do odbioru — termin do ustalenia")
     */
    public function pickupCardTimelineLabel(?\Carbon\CarbonInterface $targetDate = null): ?string
    {
        if ($this->status !== AnimalStatus::Available) {
            return null;
        }

        $pickupStart = $this->earliestPickupDate();

        if (! $pickupStart) {
            return null;
        }

        if ($this->isEarlyReservation($targetDate)) {
            $weeksUntil = $this->weeksUntilPickupWindow($targetDate);

            return "Odbiór: za ok. {$weeksUntil} tyg.";
        }

        if ($this->isOdchowany($targetDate)) {
            return 'Odbiór: termin do ustalenia';
        }

        return 'Odbiór: od zaraz';
    }

    /**
     * Badge visual variant matching <x-frontend.badge variant="...">
     */
    public function pickupBadgeVariant(?\Carbon\CarbonInterface $targetDate = null): string
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

            return $max !== null ? min((int) $max + 1, 29) : 20;
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

            return $max !== null ? min((int) $max + 1, $rangeEnd) : $rangeStart;
        }

        // 3. Kocięta (Available / Reserved): pula 1 - 19
        $max = static::whereIn('status', [AnimalStatus::Available, AnimalStatus::Reserved])
            ->whereBetween('sort_order', [1, 19])
            ->max('sort_order');

        return $max !== null ? min((int) $max + 1, 19) : 1;
    }
}
