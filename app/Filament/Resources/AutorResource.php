<?php

namespace App\Filament\Resources;

use App\Filament\Resources\AutorResource\Pages;
use App\Filament\Resources\AutorResource\RelationManagers;
use App\Models\Autor;
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
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class AutorResource extends Resource
{
    protected static ?string $model = Autor::class;

    protected static ?string $navigationIcon = 'heroicon-o-pencil';

    protected static ?string $slug = 'autores';
    public static function getNavigationLabel(): string
    {
        return __('autor.Autores');
    }

    public static function getNavigationGroup(): ?string
    {
        return __('seccao.Gerenciamento de Livros');
    }


    public static function getModelLabel(): string
    {
        return __('autor.Autores');
    }
    public static function form(Form $form): Form
    {
        return $form
        ->schema([
            Section::make(__('autor.Informação sobre o Autor'))
                    ->schema([
                        Forms\Components\TextInput::make('nome_completo')
                            ->required()
                            ->maxLength(255)
                            ->label(__('autor.nome_completo')),
                        Forms\Components\TextInput::make('nome_artistico')
                            ->label('Nome artístico')
                            ->required()
                            ->maxLength(255)
                            ->label(__('autor.nome_artistico')),
                        Forms\Components\DatePicker::make('data_de_nascimento')
                            ->required()
                            ->label(__('autor.data_de_nascimento')),
                        Forms\Components\Select::make('nacionalidade')
                            ->relationship('pais', 'official_name')
                            ->label(__('autor.País de Origem')),

            ])->columns(2)
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('nome_completo')
                    ->searchable()
                    ->label(__('autor.nome_completo')),
                Tables\Columns\TextColumn::make('nome_artistico')
                    ->searchable()
                    ->label(__('autor.nome_artistico')),
                Tables\Columns\TextColumn::make('data_de_nascimento')
                    ->date('d/m/Y')
                    ->icon('heroicon-o-calendar-days')
                    ->iconPosition(IconPosition::After)
                    ->iconColor('primary')
                    ->sortable()
                    ->label(__('autor.data_de_nascimento')),
                Tables\Columns\TextColumn::make('pais.official_name')
                    ->label(__('autor.País de Origem'))
                    ->icon('heroicon-s-globe-europe-africa')
                    ->iconPosition(IconPosition::After)
                    ->iconColor('primary')
                    ->searchable(),
                TextColumn::make('livros_count')
                    ->label(__('autor.numero_obras'))
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
                    ->label(__('autor.deleted_at')),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->icon('heroicon-o-clock')
                    ->iconPosition(IconPosition::After)
                    ->iconColor('primary')
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->label(__('autor.created_at')),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->icon('heroicon-o-clock')
                    ->iconPosition(IconPosition::After)
                    ->iconColor('primary')
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->label(__('autor.updated_at')),
            ])
            ->filters([
                SelectFilter::make('nacionalidade')
                    ->relationship('pais', 'official_name')
                    ->searchable()
                    ->preload()
                    ->label(__('autor.por_pais')),
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
            'index' => Pages\ListAutors::route('/'),
            'create' => Pages\CreateAutor::route('/create'),
            'edit' => Pages\EditAutor::route('/{record}/edit'),
        ];
    }
}
