<?php

namespace App\Models;

use App\Enums\PageTypesEnum;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Page extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'title',
        'type',
        'user_id',
        'sort',
        'slug',
        'content',
        'homepage',
        'published',
    ];

    protected $casts = [
        'type' => PageTypesEnum::class,
        'homepage' => 'boolean',
        'published' => 'boolean',
    ];

    protected static function booted(): void
    {
        static::creating(function ($page) {
            if (is_null($page->sort)) {
                $page->sort = Page::max('sort') + 1;
            }
        });

        static::saving(function (Page $page) {
            if ($page->homepage) {

                Page::where('homepage', true)
                    ->where('id', '!=', $page->id)
                    ->update(['homepage' => false]);
            }
        });
    }

    public function author()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function featuredLinks(): HasMany
    {
        return $this->hasMany(FeaturedLink::class, 'page_id');
    }
}
