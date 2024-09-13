<?php

namespace App\Filament\Resources;

use App\Filament\Resources\AluguelResource\Pages;
use App\Models\Aluguel;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Fieldset;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Actions\RestoreAction;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Table;
use Filament\Tables\Columns\BooleanColumn;
use Filament\Tables\Columns\ColorColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\Filter;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class AluguelResource extends Resource
{
    protected static ?string $model = Aluguel::class;

    protected static ?string $navigationIcon = 'heroicon-s-book-open';
    public static function getNavigationLabel(): string
    {
        return __('aluguel.Alugueis');
    }

    public static function getNavigationGroup(): ?string
    {
        return __('aluguel.Gerenciamento de Aluguéis');
    }


    public static function getModelLabel(): string
    {
        return __('aluguel.Alugueis');
    }
    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();
    }
    public static function getNavigationBadgeColor(): string|array|null
    {
        return static::getModel()::count() < 200 ? 'warning' : 'success';
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Fieldset::make('Seleção')->label(__('aluguel.Seleção'))->schema([
                    Select::make('copia_id')
                    ->relationship('copia', 'id')
                    ->getOptionLabelFromRecordUsing(fn ($record) => $record->identificador)
                    ->required()
                    ->label(__('aluguel.copia_id')),
                Select::make('funcionario_id')
                    ->relationship('funcionario', 'nome')
                    ->required()
                    ->label(__('aluguel.funcionario_id')),
                Select::make('cliente_id')
                    ->relationship('cliente', 'nome')
                    ->required()
                    ->label(__('aluguel.cliente_id')),
            ]),     
                Fieldset::make('')->schema([
                    Fieldset::make('Data de Entrega do Livro')->label(__('aluguel.funcionario_id'))->schema([
                        DatePicker::make('data_de_entrega')
                            ->label(__('aluguel.data_de_entrega'))
                            ->required(),
                       ]),
                        Fieldset::make('Estado do aluguel')->label(__('aluguel.Estado do aluguel'))->schema([
                            Toggle::make('entregue')
                            ->label(__('aluguel.entregue'))
                            ->default(false),
                        ]),
                ])->columns(1)->columnSpanFull()
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('copia.identificador')->label(__('aluguel.Livro')),
                TextColumn::make('funcionario.nome')->label(__('aluguel.Funcionário')),
                TextColumn::make('cliente.nome')->label(__('aluguel.Cliente')),
                TextColumn::make('data_de_entrega')->label(__('aluguel.Data de Entrega do Livro')),
                TextColumn::make('estado')->label(__('aluguel.estado'))
                ->badge()
                ->colors([
                    'primary'=> 'No Prazo',
                    'warning' => 'Próximo da Entrega',
                    'success' => 'Entregue',
                    'danger' => 'Em Atraso',
                ])
                ->icons([
                    'heroicon-m-x-mark'=> 'Em Atraso',
                    'heroicon-o-clock' => 'Próximo da Entrega',
                    'heroicon-m-arrow-path' => 'No Prazo',
                    'heroicon-c-book-open' => 'Entregue',
                ])
                ->iconPosition('after')
                ->formatStateUsing(fn (string $state): string => __(
                    match ($state) {
                        'Entregue' => 'aluguel.entregue',
                        'Em Atraso' => 'aluguel.atraso',
                        'Próximo da Entrega' => 'aluguel.proximo',
                        'No Prazo' => 'aluguel.prazo',
                        default => $state,
                    }
                )),
                BooleanColumn::make('entregue')->label(__('aluguel.entregue'))  
                ->searchable()
                ->toggleable(isToggledHiddenByDefault:true),
            ])
            ->filters([
                Filter::make('copia.titulo')->label(__('aluguel.Livro')),
                Filter::make('funcionario.nome')->label(__('aluguel.Funcionário')),
                Filter::make('cliente.nome')->label(__('aluguel.Cliente')),
                Filter::make('entregue')->label(__('aluguel.entregue')),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
                Tables\Actions\ViewAction::make(),
                RestoreAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ]);
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListAlugueis::route('/'),
            'create' => Pages\CreateAluguel::route('/create'),
            'edit' => Pages\EditAluguel::route('/{record}/edit'),
            'view' => Pages\ViewAluguel::route('/{record}'),
        ];
    }
}
