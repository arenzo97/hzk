<?php

namespace App\Enums;

enum PageTypesEnum: string
{
    case BASIC = 'basic';

    case MEDIA = 'media';

    case COLLECTION = 'collection';

    case FEATURE = 'feature';

    public function color()
    {
        return match($this)
        {
            self::BASIC => 'info',
            self::COLLECTION => 'warning',
            default => 'danger'
        };
    }
}
