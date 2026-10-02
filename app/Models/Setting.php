<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class Setting extends Model
{
    protected $fillable = [
        'key',
        'value',
    ];

    /**
     * Get a setting value by key, with in-memory / cache support.
     */
    public static function get(string $key, mixed $default = null): mixed
    {
        return Cache::remember("app_setting.{$key}", 3600, function () use ($key, $default) {
            $record = static::where('key', $key)->first();
            if (! $record || $record->value === null) {
                return $default;
            }

            $decoded = json_decode($record->value, true);
            return (json_last_error() === JSON_ERROR_NONE) ? $decoded : $record->value;
        });
    }

    /**
     * Set a setting value by key and invalidate cache.
     */
    public static function set(string $key, mixed $value): static
    {
        $serialized = is_array($value) || is_object($value)
            ? json_encode($value, JSON_UNESCAPED_UNICODE)
            : (string) $value;

        $record = static::updateOrCreate(
            ['key' => $key],
            ['value' => $serialized]
        );

        Cache::forget("app_setting.{$key}");

        return $record;
    }

    /**
     * Quick helper to get the active Blog Closing CTA / Cattery Offer.
     */
    public static function getBlogCta(): array
    {
        $defaults = [
            'is_enabled'    => true,
            'badge'         => 'Hodowla Kotów z Mazowieckiej Szwajcarii',
            'heading'       => '🐾 Dostępne kocięta w naszej hodowli',
            'body'          => '',
            'image_url'     => '',
            'button_text'   => 'Zobacz dostępne koty',
            'button_url'    => '/koty',
            'facebook_info' => 'Zapraszamy również na nasz profil na Facebooku — publikujemy tam bieżące relacje, codzienne życie kociąt oraz nowe zdjęcia i filmy!',
            'facebook_text' => 'Odwiedź nas na Facebooku',
            'facebook_url'  => 'https://www.facebook.com/profile.php?id=61580668026948',
        ];

        $data = static::get('blog_closing_cta', []);

        return is_array($data) ? array_merge($defaults, $data) : $defaults;
    }
}
