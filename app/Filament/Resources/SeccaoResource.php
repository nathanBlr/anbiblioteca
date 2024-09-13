<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SeccaoResource\Pages;
use App\Filament\Resources\SeccaoResource\RelationManagers;
use App\Filament\Resources\SeccaoResource\RelationManagers\LivrosRelationManager;
use App\Models\Seccao;
use Filament\Forms;
use Filament\Forms\Components\Fieldset;
use Filament\Forms\Components\Section;
use Filament\Forms\Form;
use Filament\Infolists\Components\Fieldset as InfolistFieldset;
use Filament\Infolists\Components\Section as InfolistSection;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Infolist;
use Filament\Resources\Resource;
use Filament\Support\Enums\IconPosition;
use Filament\Tables;
use Filament\Tables\Actions\ActionGroup;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Actions\RestoreAction;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class SeccaoResource extends Resource
{
    protected static ?string $model = Seccao::class;

    protected static ?string $navigationIcon = 'heroicon-o-bookmark-square';

    protected static ?string $slug = 'seccoes';
    public static function getNavigationLabel(): string
    {
        return __('seccao.Secções');
    }

    public static function getNavigationGroup(): ?string
    {
        return __('seccao.Gerenciamento de Livros');
    }


    public static function getModelLabel(): string
    {
        return __('seccao.Secções');
    }
    public static function form(Form $form): Form
    {
        return $form
            ->schema([
            Section::make(__('seccao.Sobre:'))
                ->schema([
                    Forms\Components\TextInput::make('nome')
                        ->required()
                        ->maxLength(255)
                        ->label(__('seccao.nome')),
                    Forms\Components\TextInput::make('descricao')
                        ->maxLength(255)
                        ->label(__('seccao.descricao')),
 

                    Fieldset::make(__('seccao.local_estante'))
                        ->schema([
                            Forms\Components\TextInput::make('corredor')
                            ->required()
                            ->numeric()
                            ->maxLength(3)
                            ->label(__('seccao.corredor')),
                        Forms\Components\TextInput::make('estante')
                            ->required()
                            ->maxLength(1)
                            ->label(__('seccao.estande')),
                        Forms\Components\TextInput::make('capacidade')
                            ->required()
                            ->numeric()
                            ->label(__('seccao.capacidade')),
                        ])->columns(3)
            ])->columns(2)
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('nome')
                    ->searchable()
                    ->label(__('seccao.nome')),
                Tables\Columns\TextColumn::make('descricao')
                    ->searchable()
                    ->label(__('seccao.descricao')),
                Tables\Columns\TextColumn::make('localizacao')
                    ->label(__('seccao.localizacao'))
                    ->sortable(),
                Tables\Columns\TextColumn::make('capacidade')
                    ->label(__('seccao.capacidade'))
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('deleted_at')
                    ->dateTime()
                    ->sortable()
                    ->icon('heroicon-o-trash')
                    ->iconPosition(IconPosition::After)
                    ->iconColor('danger')
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->label(__('seccao.deleted_at')),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->icon('heroicon-o-clock')
                    ->iconPosition(IconPosition::After)
                    ->iconColor('primary')
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->label(__('seccao.created_at')),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->icon('heroicon-o-clock')
                    ->iconPosition(IconPosition::After)
                    ->iconColor('primary')
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->label(__('seccao.updated_at')),
            ])
            ->filters([
                //
            ])
            ->actions([
                ActionGroup::make([
                    Tables\Actions\ViewAction::make(),
                    Tables\Actions\EditAction::make(),
                    DeleteAction::make(),
                    RestoreAction::make()
                ])
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }

    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                InfolistSection::make(__('seccao.Sobre:'))
                    ->schema([
                        TextEntry::make('nome')
                            ->label(__('seccao.nome')),
                        TextEntry::make('descricao')
                            ->placeholder(__('seccao.descricao_null'))
                            ->label(__('seccao.descricao')),
                        InfolistFieldset::make(__('seccao.local_estante'))
                            ->schema([
                                TextEntry::make('corredor')
                                    ->label(__('seccao.corredor')),
                                TextEntry::make('estante')
                                    ->label(__('seccao.estande')),
                                TextEntry::make('capacidade')
                                    ->label(__('seccao.capacidade')),
                            ])->columns(3)
                    ])->columns(2)
            ]);
    }

    public static function getRelations(): array
    {
        return [
            LivrosRelationManager::class
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListSeccaos::route('/'),
            'create' => Pages\CreateSeccao::route('/create'),
            'view' => Pages\ViewSeccao::route('/{record}'),
            'edit' => Pages\EditSeccao::route('/{record}/edit'),
        ];
    }
}
