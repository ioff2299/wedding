<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SiteBlock extends Model
{
    protected $fillable = ['key', 'title', 'content', 'image_path', 'is_visible'];

    protected $casts = [
        'content'     => 'array',
        'is_visible'  => 'boolean',
    ];

    public static function getByKey(string $key): ?self
    {
        return static::where('key', $key)->first();
    }

    public static function getAllKeyed(): array
    {
        return static::all()->keyBy('key')->map(fn($b) => array_merge(
            [
                'title'       => $b->title,
                'image_path'  => $b->image_path,
                'is_visible'  => $b->is_visible,
            ],
            $b->content ?? []
        ))->toArray();
    }
}
