<?php

declare(strict_types=1);

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Services\MediaService;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Str;
use Laravel\Fortify\TwoFactorAuthenticatable;

class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable, TwoFactorAuthenticatable, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        /**
         * Omdat we User::create([...]) gebruiken,
         * moeten deze velden mass assignable zijn.
         * Anders gooit Laravel MassAssignmentException.
         */
        'name',
        'email',
        'password',
        'role_id',
        'is_active',
        'email_verified_at',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'two_factor_secret',
        'two_factor_recovery_codes',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'password' => 'hashed',
            'is_active' => 'boolean',
            'email_verified_at' => 'datetime',
        ];
    }

    /**
     * Get the user's initials
     */
    public function initials(): string
    {
        return Str::of($this->name)
            ->explode(' ')
            ->take(2)
            ->map(fn ($word) => Str::substr($word, 0, 1))
            ->implode('');
    }

    // Relaties
    public function role(): BelongsTo
    {
        return $this->belongsTo(Role::class);
    }
    /**
     * Relatie: één user kan meerdere posts schrijven.
     */
    public function posts()
    {
        return $this->hasMany(Post::class);
    }
    public function media(): MorphOne
    {
        return $this->morphOne(Media::class, 'mediable');
    }
    public function scopeSearch(Builder $query, string $q): Builder
    {
        $q = trim($q);

        if ($q === '') {
            return $query;
        }

        return $query->where(function (Builder $sub) use ($q) {
            $sub->where('name', 'like', "%{$q}%")
                ->orWhere('email', 'like', "%{$q}%");
        });
    }

    public function scopeRoleFilter(Builder $query, ?int $roleId): Builder
    {
        if (! $roleId) {
            return $query;
        }

        return $query->where('role_id', $roleId);
    }

    public function scopeStatusFilter(Builder $query, ?string $status): Builder
    {
        if (! $status) {
            return $query;
        }

        return match ($status) {
            'active' => $query->where('is_active', 1),
            'inactive' => $query->where('is_active', 0),
            default => $query,
        };
    }

    public function scopeVerifiedFilter(Builder $query, ?string $verified): Builder
    {
        if (! $verified) {
            return $query;
        }

        return match ($verified) {
            'yes' => $query->whereNotNull('email_verified_at'),
            'no' => $query->whereNull('email_verified_at'),
            default => $query,
        };
    }
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
    public function scopeSortBySafe(Builder $query, string $sort, string $dir): Builder
    {
        // Extra defensief, zelfs al valideert de FormRequest dit al
        $allowed = ['id', 'name', 'email', 'created_at', 'is_active'];

        if (! in_array($sort, $allowed, true)) {
            $sort = 'created_at';
        }

        $dir = strtolower($dir) === 'asc' ? 'asc' : 'desc';

        return $query->orderBy($sort, $dir);
    }
    protected static function booted()
    {
        static::forceDeleted(function ($user) {

            if ($user->media) {

                $mediaService = app(MediaService::class);

                $mediaService->delete($user->media);

            }

        });
    }



}
