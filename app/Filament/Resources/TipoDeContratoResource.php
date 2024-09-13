<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TipoDeContratoResource\Pages;
use App\Filament\Resources\TipoDeContratoResource\RelationManagers;
use App\Models\TipoDeContrato;
use Filament\Forms;
use Filament\Forms\Components\Fieldset;
use Filament\Forms\Components\Section;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class TipoDeContratoResource extends Resource
{
    protected static ?string $model = TipoDeContrato::class;

    protected static ?string $navigationIcon = 'heroicon-o-document';
    protected static ?int $navigationSort = 2;
    public static function getNavigationGroup(): ?string
    {
        return __('funcionario.Staff');
    }
    public static function getNavigationLabel(): string
    {
        return __('tipo.Tipo de Funcionário');
    }
    public static function getModelLabel(): string
    {
        return __('tipo.tipo');
    }
    
    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make()->schema([
                    Fieldset::make('Dados Necessários')->label(__('tipo.Dados Necessários'))->schema([
                        Forms\Components\TextInput::make('nome')
                        ->required()
                        ->maxLength(255)
                        ->label(__('tipo.nome')),
                    Forms\Components\Textarea::make('descricao')
                        ->maxLength(65535)
                        ->columnSpanFull()
                        ->label(__('tipo.descricao')),
                    ])
                ])
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('nome')
                    ->searchable()
                    ->label(__('tipo.nome')),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->label(__('tipo.created_at')),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->label(__('tipo.updated_at')),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
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
            'index' => Pages\ListTipoDeContratos::route('/'),
            'create' => Pages\CreateTipoDeContrato::route('/create'),
            'edit' => Pages\EditTipoDeContrato::route('/{record}/edit'),
        ];
    }
}
