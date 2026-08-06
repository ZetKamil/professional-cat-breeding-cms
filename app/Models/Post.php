<?php

declare(strict_types=1);

namespace App\Models;

use App\Concerns\RecordUserActivity;
use App\Services\MediaService;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Post extends Model
{
    use HasFactory;
    use RecordUserActivity;
    use SoftDeletes;

    /**
     * Velden die via mass assignment ingevuld mogen worden.
     */
    protected $fillable = [
        'user_id',
        'title',
        'slug',
        'excerpt',
        'body',
        'is_published',
        'published_at',
        'created_by',
        'updated_by',
    ];

    /**
     * Type casting voor correcte PHP-types.
     */
    protected function casts(): array
    {
        return [
            'is_published' => 'boolean',
            'published_at' => 'datetime',
        ];
    }

    /**
     * We willen slug-based route model binding:
     * /backend/posts/mijn-slug
     * in plaats van
     * /backend/posts/1
     */
    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    /**
     * Relatie: één post hoort bij één auteur.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relatie: één post kan meerdere categories hebben.
     */
    public function categories()
    {
        return $this->belongsToMany(Category::class)->withTimestamps();
    }

    public function media(): MorphOne
    {
        return $this->morphOne(Media::class, 'mediable');
    }

    /**
     * Automatische slug-generatie bij create/update.
     *
     * Let op:
     * - als de gebruiker zelf een slug invult, laten we die staan
     * - als slug leeg is, maken we hem op basis van de title
     */
    protected static function booted(): void
    {
        static::creating(function (Post $post) {
            if (blank($post->slug) && filled($post->title)) {
                $post->slug = Str::slug($post->title);
            }
        });

        static::updating(function (Post $post) {
            if ($post->isDirty('title') && blank($post->slug)) {
                $post->slug = Str::slug($post->title);
            }
        });

        static::forceDeleted(function ($post) {
            if ($post->media) {
                $mediaService = app(MediaService::class);
                $mediaService->delete($post->media);
            }
        });
    }

    /**
     * Zoekscope voor vrije tekst.
     */
    public function scopeSearch(Builder $query, string $q): Builder
    {
        $q = trim($q);

        if ($q === '') {
            return $query;
        }

        return $query->where(function (Builder $sub) use ($q) {
            $sub->where('title', 'like', "%{$q}%")
                ->orWhere('slug', 'like', "%{$q}%")
                ->orWhere('excerpt', 'like', "%{$q}%")
                ->orWhere('body', 'like', "%{$q}%");
        });
    }

    /**
     * Filter op auteur.
     */
    public function scopeAuthorFilter(Builder $query, ?int $userId): Builder
    {
        if (! $userId) {
            return $query;
        }

        return $query->where('user_id', $userId);
    }

    /**
     * Filter op publicatiestatus.
     */
    public function scopeStatusFilter(Builder $query, ?string $status): Builder
    {
        if (! $status) {
            return $query;
        }

        return match ($status) {
            'published' => $query->where('is_published', true),
            'draft' => $query->where('is_published', false),
            default => $query,
        };
    }

    public function scopePublished(Builder $query): Builder
    {
        return $query
            ->where('is_published', true)
            ->whereNotNull('published_at')
            ->where('published_at', '<=', now());
    }

    /**
     * Filter op category.
     */
    public function scopeCategoryFilter(Builder $query, ?int $categoryId): Builder
    {
        if (! $categoryId) {
            return $query;
        }

        return $query->whereHas('categories', function (Builder $sub) use ($categoryId) {
            $sub->where('categories.id', $categoryId);
        });
    }

    /**
     * Filter op soft deleted records.
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
     * Veilige sortering op whitelisted kolommen.
     */
    public function scopeSortBySafe(Builder $query, string $sort, string $dir): Builder
    {
        $allowed = ['id', 'title', 'slug', 'created_at', 'published_at', 'is_published'];

        if (! in_array($sort, $allowed, true)) {
            $sort = 'created_at';
        }

        $dir = strtolower($dir) === 'asc' ? 'asc' : 'desc';

        return $query->orderBy($sort, $dir);
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function editor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    /**
     * Pobiera URL okładki artykułu dopasowany do tematyki posta.
     */
    public function coverImageUrl(): string
    {
        if ($this->media) {
            return $this->media->url();
        }

        $slug = $this->slug ?? Str::slug($this->title ?? '');

        if (str_contains($slug, 'badania-genetyczne') || str_contains($slug, 'zdrowie')) {
            return 'https://images.unsplash.com/photo-1576201836106-db1758fd1c97?q=80&w=1200&auto=format&fit=crop';
        }

        if (str_contains($slug, 'wyprawka')) {
            return 'https://images.unsplash.com/photo-1533738363-b7f9aef128ce?q=80&w=1200&auto=format&fit=crop';
        }

        if (str_contains($slug, 'bengalski') || str_contains($slug, 'bengal')) {
            return 'https://images.unsplash.com/photo-1513360371669-4adf3dd7dff8?q=80&w=1200&auto=format&fit=crop';
        }

        if (str_contains($slug, 'brytyjczyk') || str_contains($slug, 'brytyjski')) {
            return 'https://images.unsplash.com/photo-1573865526739-10659fec78a5?q=80&w=1200&auto=format&fit=crop';
        }

        if (str_contains($slug, 'syjamsk') || str_contains($slug, 'syjam')) {
            return 'https://images.unsplash.com/photo-1513245543132-31f507417b26?q=80&w=1200&auto=format&fit=crop';
        }

        if (str_contains($slug, 'dieta') || str_contains($slug, 'zywienie') || str_contains($slug, 'barf')) {
            return 'https://images.unsplash.com/photo-1589924691995-400dc9ecc119?q=80&w=1200&auto=format&fit=crop';
        }

        return 'https://images.unsplash.com/photo-1514888286974-6c03e2ca1dba?q=80&w=1200&auto=format&fit=crop';
    }
}
