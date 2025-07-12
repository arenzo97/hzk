<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FeaturedLink extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'name',
        'label',
        'url',
    ];

    protected $casts = [];
}
