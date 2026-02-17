<?php

namespace App\Models;

use App\Models\Page;

class Collectable extends Page
{

    public function collectables()
    {
        return $this->morphToMany(Page::class, 'collectable');
   
    }
}
