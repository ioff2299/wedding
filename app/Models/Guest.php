<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Guest extends Model
{
    protected $fillable = [
        'user_token',
        'name',
        'attending',
        'food_preference',
        'alcohol_preferences',
        'food_allergy',
    ];

    protected $casts = [
        'attending' => 'boolean',
        'alcohol_preferences' => 'array',
    ];
}
