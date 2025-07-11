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
                ->icon('heroicon-o-eye')
                ->color('info')
                ->action(function ($record, $livewire) {
                    $record->update(['published' => true]);

                    return $livewire->redirect(PageResource::getUrl('index'));
                })
                ->visible(fn ($record) => $record->published === false),
            Action::make('unpublish')
                ->label('Set to draft')
                ->icon('heroicon-o-eye-slash')
                ->color('danger')
                ->action(function ($record, $livewire) {
                    $record->update(['published' => false]);

                    return $livewire->redirect(PageResource::getUrl('index'));
                })
                ->visible(fn ($record) => $record->published === true),
            DeleteAction::make()
                ->icon('heroicon-o-trash')
                ->iconButton(),
        ];
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('view', ['record' => $this->record]);
    }
}
