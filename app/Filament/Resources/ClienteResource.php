<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use App\Models\Cliente;
use App\Filament\Resources\ClienteResource\Pages;
use Filament\Actions\DeleteAction;
use Filament\Forms\Components\Fieldset;
use Filament\Forms\Components\Group;
use Illuminate\Support\Facades\Blade;
use Barryvdh\DomPDF\Facade\Pdf;
use Filament\Actions\ExportAction;
use App\Filament\Exports\ProductExporter;
use Filament\Forms\Components\Section;
use Filament\Support\Enums\IconPosition;
use Filament\Tables\Actions\RestoreAction;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ClienteResource extends Resource
{
    protected static ?string $model = Cliente::class;

    protected static ?string $navigationIcon = 'heroicon-o-users';
    protected static ?string $navigationLabel = 'Clientes';
    protected static ?string $navigationGroup = 'Área dos Clientes';
    protected static ?int $navigationSort = 1;
    protected static ?int $navigationGroupSort = 2;

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();
    }
    public static function getNavigationBadgeColor(): string|array|null
    {
        return static::getModel()::count() < 200 ? 'warning' : 'success';
    }
    public static function getNavigationLabel(): string
    {
        return __('cliente.Clientes');
    }

    public static function getNavigationGroup(): ?string
    {
        return __('cliente.Área dos Clientes');
    }


    public static function getModelLabel(): string
    {
        return __('cliente.Clientes');
    }
    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make()->schema([
                    Fieldset::make('Informaçao Pessoal')->label(__('cliente.Informaçao Pessoal'))->schema([
                        TextInput::make('nome')
                        ->required()
                        ->maxLength(255)
                        ->label(__('cliente.nome')),
                    TextInput::make('nome_completo')
                        ->required()
                        ->maxLength(255)
                        ->label(__('cliente.nome_completo')),
                    DatePicker::make('data_de_nascimento')
                        ->required()
                        ->label(__('cliente.data_de_nascimento')),
                    Fieldset::make('Contatos e Endereço')->label(__('cliente.Contatos e Endereço'))->schema([
                        TextInput::make('morada')
                        ->required()
                        ->maxLength(255)
                        ->label(__('cliente.morada')),
                    TextInput::make('numero_de_telemovel')
                        ->tel()
                        ->maxLength(255)
                        ->label(__('cliente.numero_de_telemovel')),
                    TextInput::make('email')
                        ->email()
                        ->maxLength(255)
                        ->label(__('cliente.email')),
                    TextInput::make('codigo_postal')
                        ->maxLength(255)
                        ->label(__('cliente.codigo_postal')),
                    ]),
                        Fieldset::make('Dados Cartão')->label(__('cliente.Dados Cartão'))->schema([
                            Group::make([
                                Toggle::make('cartao')
                                ->reactive()
                                ->required()
                                ->label(__('cliente.cartao')),
                            DatePicker::make('validade_cartao')
                                ->label(__('cliente.validade_cartao'))
                                ->visible(fn ($get) => $get('cartao')),
                            TextInput::make('nome_cartao')
                                ->maxLength(255)
                                ->label(__('cliente.nome_cartao'))
                                ->visible(fn ($get) => $get('cartao')),
                            TextInput::make('numero_cartao')
                                ->maxLength(255)
                                ->label(__('cliente.numero_cartao'))
                                ->visible(fn ($get) => $get('cartao')),
                            ])->columns(2)->columnSpanFull()
                        ])
                    ]),
                  
                ])  
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('nome')
                    ->searchable()
                    ->label(__('cliente.nome')),
                Tables\Columns\TextColumn::make('nome_completo')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->label(__('cliente.nome_completo')),
                Tables\Columns\TextColumn::make('data_de_nascimento')
                    ->date()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->label(__('cliente.data_de_nascimento')),
                Tables\Columns\TextColumn::make('morada')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->label(__('cliente.morada')),
                Tables\Columns\TextColumn::make('numero_de_telemovel')
                    ->searchable()
                    ->label(__('cliente.numero_de_telemovel')),
                Tables\Columns\TextColumn::make('email')
                    ->searchable()
                    ->label(__('cliente.email')),
                Tables\Columns\IconColumn::make('cartao')
                    ->boolean()
                    ->label(__('cliente.cartao')),
                Tables\Columns\TextColumn::make('deleted_at')
                    ->dateTime()
                    ->sortable()
                    ->icon('heroicon-o-trash')
                    ->iconPosition(IconPosition::After)
                    ->iconColor('danger')
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->label(__('cliente.deleted_at')),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->label(__('cliente.created_at')),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->label(__('cliente.updated_at')),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
                //ExportAction::make()
                    //->exporter(App\Filament\Resources\ProductExporter::class),
                Tables\Actions\Action::make('pdf') 
                ->label('PDF')
                ->color('success')
                ->icon('heroicon-o-document')
                ->action(function (Cliente $record) {
                    return response()->streamDownload(function () use ($record) {
                        echo Pdf::loadHtml(
                            Blade::render('pdf', ['record' => $record])
                        )->stream();
                    }, $record->nome . '.pdf');
                }),
                Tables\Actions\ViewAction::make('view'),
                RestoreAction::make(),
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
            'index' => Pages\ListClientes::route('/'),
            'create' => Pages\CreateCliente::route('/create'),
            'edit' => Pages\EditCliente::route('/{record}/edit'),
            'view' => Pages\ViewCliente::route('/{record}'),
        ];
    }
}
