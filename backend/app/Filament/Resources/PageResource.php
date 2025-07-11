<?php

namespace App\Filament\Resources;

use App\Enums\PageTypesEnum;
use App\Filament\Resources\PageResource\Pages;
use App\Models\Page;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\ToggleButtons;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Forms\Components\Section;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Support\Str;

class PageResource extends Resource
{
    protected static ?string $model = Page::class;

    protected static ?string $navigationGroup = 'Website';

    protected static ?string $navigationIcon = 'heroicon-o-book-open';

    protected static ?string $navigationLabel = 'Pages';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Details')
                    ->columns(2)
                    ->schema([
                        TextInput::make('title')
                            ->required()
                            ->maxLength(255)
                            ->live(onBlur: true)
                            ->afterStateUpdated(fn ($state, callable $set) => $set('slug', Str::slug($state))),
                        TextInput::make('slug')
                            ->maxLength(255)
                            ->unique(ignoreRecord: true),
                        ToggleButtons::make('homepage')
                            ->label('Homepage')
                            ->boolean()
                            ->grouped()
                            ->options([
                                false => 'No',
                                true => 'Yes',
                            ])
                            ->live()
                            ->default(false),
                    ]),
                Section::make('Page options')
                    ->columns(2)
                    ->visible(fn (Get $get) => $get('homepage') == false)
                    ->schema([
                        ToggleButtons::make('type')
                            ->label('Type')
                            ->inline()
                            ->icons(collect(PageTypesEnum::cases())->mapWithKeys(function ($case) {
                                return [$case->value => $case->icon()];
                            }))
                            ->options(collect(PageTypesEnum::cases())->mapWithKeys(function ($case) {
                                return [$case->value => $case->title()];
                            }))
                            ->default(PageTypesEnum::BASIC->value),
                    ]),
                RichEditor::make('content')
                    ->label('Page Content')
                    ->fileAttachmentsDirectory('pages')
                    ->fileAttachmentsDisk('public'),
            ])
            ->columns(1);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->reorderable('sort')
            ->defaultSort('sort')
            ->columns([
                IconColumn::make('type')
                    ->color(fn ($record): string => $record->homepage === true ? 'primary': '')
                    ->icon(fn (string $state, $record): string => $record->homepage === true ? 'heroicon-o-star': PageTypesEnum::from($state)->icon()),
                TextColumn::make('title')->label('Title')->searchable(),
                TextColumn::make('slug')->label('Slug')->searchable(),
                TextColumn::make('author.name')
                    ->label('Author')
                    ->sortable()
                    ->searchable(),
                IconColumn::make('published')
                    ->label('Published')
                    ->boolean()
                    ->trueIcon('heroicon-o-check-circle')
                    ->falseIcon('heroicon-o-x-circle')
                    ->trueColor('success')
                    ->falseColor('danger')
                    ->tooltip(fn (bool $state) => $state ? 'Published' : 'Draft'),
                TextColumn::make('created_at')
                    ->label('Created')
                    ->dateTime('M j, Y H:i'),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPages::route('/'),
            'create' => Pages\CreatePage::route('/create'),
            'edit' => Pages\EditPage::route('/{record}/edit'),
        ];
    }
}
