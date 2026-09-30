<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TrendingTopic extends Model
{
    protected $fillable = [
        'breed',
        'fetched_date',
        'topics',
    ];

    protected function casts(): array
    {
        return [
            'fetched_date' => 'date:Y-m-d',
            'topics'       => 'array',
        ];
    }
}
