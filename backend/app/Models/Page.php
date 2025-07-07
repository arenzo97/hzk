<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

class Page extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'title',
        'user_id',
        'slug',
        'content',
        'published',
    ];

    protected $casts = [
        'published' => 'boolean',
    ];

    public function author()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
