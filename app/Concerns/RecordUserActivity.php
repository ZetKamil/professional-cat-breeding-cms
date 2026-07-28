<?php

declare(strict_types=1);

namespace App\Concerns;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

trait RecordUserActivity
{
    protected static function bootRecordUserActivity(): void
    {
        static::creating(function (Model $model): void {
            if (Auth::check() && blank($model->created_by)) {
                $model->created_by = Auth::id();
            }

            if (Auth::check() && blank($model->updated_by)) {
                $model->updated_by = Auth::id();
            }
        });

        static::updating(function (Model $model): void {
            if (Auth::check()) {
                $model->updated_by = Auth::id();
            }
        });
    }
}
