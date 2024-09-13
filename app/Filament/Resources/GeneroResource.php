<?php

namespace App\Filament\Resources;

use App\Filament\Resources\GeneroResource\Pages;
use App\Filament\Resources\GeneroResource\RelationManagers;
use App\Models\Genero;
use Filament\Forms;
use Filament\Forms\Components\Fieldset;
use Filament\Forms\Components\Section;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Support\Enums\IconPosition;
use Filament\Tables;
use Filament\Tables\Actions\ActionGroup;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Actions\RestoreAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class GeneroResource extends Resource
{
    protected static ?string $model = Genero::class;

    protected static ?string $navigationIcon = 'heroicon-o-bookmark';

    public static function getNavigationLabel(): string
    {
        return __('genero.Genero');
    }

    public static function getNavigationGroup(): ?string
    {
        return __('seccao.Gerenciamento de Livros');
    }


    public static function getModelLabel(): string
    {
        return __('genero.Genero');
    }
    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make(__('genero.Infromação sobre o Genero Literario'))
                    ->schema([
                        Forms\Components\TextInput::make('nome')
                            ->required()
                            ->maxLength(255)
                            ->label(__('genero.nome')),
                            
                        Forms\Components\TextInput::make('descricao')
                            ->maxLength(255)
                            ->label(__('genero.descricao')),
                    ])
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('nome')
                    ->searchable()
                    ->label(__('genero.nome')),
                Tables\Columns\TextColumn::make('descricao')
                    ->searchable()
                    ->label(__('genero.descricao')),
                TextColumn::make('livros_count')
                    ->label(__('genero.numero_obras'))
                    ->counts('livros')
                    ->badge()
                    ->color('info')
                    ->sortable(),
                Tables\Columns\TextColumn::make('deleted_at')
                    ->dateTime()
                    ->sortable()
                    ->icon('heroicon-o-trash')
                    ->iconPosition(IconPosition::After)
                    ->iconColor('danger')
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->label(__('genero.deleted_at')),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->icon('heroicon-o-clock')
                    ->iconPosition(IconPosition::After)
                    ->iconColor('primary')
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->label(__('genero.created_at')),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->icon('heroicon-o-clock')
                    ->iconPosition(IconPosition::After)
                    ->iconColor('primary')
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->label(__('genero.updated_at')),
            ])
            ->filters([
                //
            ])
            ->actions([
                ActionGroup::make([
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

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListGeneros::route('/'),
            'create' => Pages\CreateGenero::route('/create'),
            'edit' => Pages\EditGenero::route('/{record}/edit'),
        ];
    }
}
