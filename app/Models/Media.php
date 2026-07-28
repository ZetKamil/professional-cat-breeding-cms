<?php

namespace App\Models;

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
    ];

    public function mediable(): MorphTo
    {
        return $this->morphTo();
    }

    public function path(): string
    {
        if ($this->directory) {
            return $this->directory . '/' . $this->filename;
        }

        return $this->filename;
    }

    public function url(): string
    {
        return asset('storage/' . $this->path());
    }
    public function isImage(): bool
    {
        if (!$this->mime_type) {
            return false;
        }

        return str_starts_with($this->mime_type, 'image/');
    }
}
