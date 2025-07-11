<?php

namespace App\Filament\Resources\PageResource\Pages;

use App\Filament\Resources\PageResource;
use Filament\Actions\Action;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditPage extends EditRecord
{
    protected static string $resource = PageResource::class;

    protected function getHeaderActions(): array
    {
        return [

            Action::make('publish')
                ->icon('heroicon-o-check-badge')
                ->color('info')
                ->action(fn($record) => $record->update(['published' => true]))
                ->visible(fn($record) => $record->published === false),
                Action::make('unpublish')
                ->label('Set to draft')
                ->icon('heroicon-o-pencil')
                ->color('danger')
                ->action(fn($record) => $record->update(['published' => false]))
                ->visible(fn($record) => $record->published === true),
            DeleteAction::make()
                ->icon('heroicon-o-trash')
                ->iconButton(),
        ];
    }
}
