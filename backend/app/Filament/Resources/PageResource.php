<?php

namespace App\Filament\Resources;

use App\Enums\PageTypesEnum;
use App\Filament\Resources\PageResource\Pages;
use App\Models\Page;
use Filament\Forms\Components\Actions\Action;
use Filament\Forms\Components\Fieldset;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\ToggleButtons;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Forms\Set;
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
                            ->live()
                            ->icons(collect(PageTypesEnum::cases())->mapWithKeys(function ($case) {
                                return [$case->value => $case->icon()];
                            }))
                            ->options(collect(PageTypesEnum::cases())->mapWithKeys(function ($case) {
                                return [$case->value => $case->title()];
                            }))
                            ->default(PageTypesEnum::BASIC->value),
                        Repeater::make('featured_links')
                            ->relationship('featuredLinks')
                            ->label('Featured Links')
                            ->visible(fn (Get $get): bool => $get('type') === PageTypesEnum::FEATURE->value)
                            ->itemLabel(fn (array $state): ?string => $state['name'] ?? 'New featured link')
                            ->collapsible()
                            ->cloneable()
                            ->reorderable()
                            ->addable(true)
                            ->deletable(true)
                            ->schema([
                                Fieldset::make()
                                    ->schema([
                                        TextInput::make('label')
                                            ->readonly()
                                            ->label(false)
                                            ->prefixIcon('heroicon-o-pencil-square'),
                                        TextInput::make('url')
                                            ->readonly()
                                            ->label(false)
                                            ->prefixIcon('heroicon-o-globe-alt'),
                                        TextInput::make('name')
                                            ->live()
                                            ->label('Link Name (Click Edit to change)')
                                            ->readOnly()
                                            ->default(fn (Get $get) => $get('label') ?? $get('name'))
                                            ->hiddenLabel()
                                            ->suffixAction(
                                                Action::make('editItemDetails')
                                                    ->label('Edit Details')
                                                    ->icon('heroicon-o-arrows-pointing-out')
                                                    ->color('secondary')
                                                    ->outlined()
                                                    ->disabled(fn (string $operation): bool => $operation === 'view')
                                                    ->modalHeading(function (Get $get): string {
                                                        return 'Edit Featured Link: '.($get('label') ?? $get('name') ?? 'New Link');
                                                    })
                                                    ->form([
                                                        TextInput::make('name')
                                                            ->required()
                                                            ->maxLength(255)
                                                            ->label('Internal Name (Admin Only)')
                                                            ->helperText('For admin use, to help identify featured links'),
                                                        TextInput::make('label')
                                                            ->nullable()
                                                            ->label('Public-Facing Label')
                                                            ->helperText('Public facing, what users will see on the website for this link'),
                                                        TextInput::make('url')
                                                            ->url()
                                                            ->nullable()
                                                            ->prefix('https://')
                                                            ->suffixIcon('heroicon-m-globe-alt')
                                                            ->label('Link URL'),
                                                    ])
                                                    ->fillForm(function (Get $get): array {
                                                        return [
                                                            'name' => $get('name'),
                                                            'label' => $get('label'),
                                                            'url' => $get('url'),
                                                        ];
                                                    })
                                                    ->action(function (array $data, Set $set): void {
                                                        $set('name', $data['name']);
                                                        $set('label', $data['label']);
                                                        $set('url', $data['url']);
                                                    })
                                                    ->slideOver()
                                            )->columnSpanFull(),
                                    ]),

                            ]),

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
                    ->color(fn ($record): string => $record->homepage === true ? 'primary' : '')
                    ->icon(fn (?PageTypesEnum $state, $record): string => $record->homepage === true
                            ? 'heroicon-o-star'
                            : ($state?->icon() ?? 'heroicon-o-question-mark-circle')
                    ),
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
            'view' => Pages\ViewPage::route('/{record}'),
            'edit' => Pages\EditPage::route('/{record}/edit'),
        ];
    }
}
