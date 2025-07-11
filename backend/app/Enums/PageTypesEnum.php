<?php

namespace App\Enums;

use Illuminate\Support\Str;

enum PageTypesEnum: string
{
    case BASIC = 'basic';

    case MEDIA = 'media';

    case COLLECTION = 'collection';

    case FEATURE = 'feature';

    public function icon()
    {
        return match ($this) {
            self::MEDIA => 'heroicon-o-photo',
            self::COLLECTION => 'heroicon-o-square-3-stack-3d',
            self::FEATURE => 'heroicon-o-sparkles',
            default => 'heroicon-o-document-text'
        };
    }

    public function title(): string
    {
        return ucfirst(Str::headline($this->value));
    }

    public static function icons(): array
    {
        return collect(self::cases())
            ->mapWithKeys(fn (self $enum) => [
                $enum->value => $enum->icon(),
            ])
            ->toArray();
    }
}
