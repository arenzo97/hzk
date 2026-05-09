<?php

namespace App\Models;

class Collectable extends Page
{
    public function collectables()
    {
        return $this->morphToMany(Page::class, 'collectable');

    }
}
