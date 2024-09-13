<?php

namespace App\Filament\Resources;

use App\Filament\Resources\FuncionarioResource\Pages;
use App\Filament\Resources\FuncionarioResource\RelationManagers;
use App\Models\Funcionario;
use Barryvdh\DomPDF\Facade\Pdf;
use Filament\Forms;
use Filament\Forms\Components\Fieldset;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Support\Enums\IconPosition;
use Filament\Tables;
use Filament\Tables\Actions\RestoreAction;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\Blade;
use Illuminate\Validation\Rules\Password;

class FuncionarioResource extends Resource
{
    protected static ?string $model = Funcionario::class;

    protected static ?string $navigationIcon = 'heroicon-o-users';
    //protected static ?string $navigationLabel = 'Funcionários';
    public static function getNavigationLabel(): string
    {
        return __('funcionario.Funcionários');
    }

    public static function getNavigationGroup(): ?string
    {
        return __('funcionario.Staff');
    }


    public static function getModelLabel(): string
    {
        return __('funcionario.Funcionario');
    }
    
    protected static ?int $navigationSort = 1;
    protected static ?int $navigationGroupSort = 1;

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
                Section::make()->schema([
                    Fieldset::make('Dados Pessoais')->label(__('funcionario.Dados Pessoais'))->schema([
                        Forms\Components\TextInput::make('nome')
                        ->required()
                        ->maxLength(255)
                        ->label(__('funcionario.nome')),
                    Forms\Components\TextInput::make('nome_completo')
                        ->label(__('funcionario.nome_completo'))
                        ->required()
                        ->maxLength(255),
                    Forms\Components\DatePicker::make('data_de_nascimento')
                        ->required()
                        ->label(__('funcionario.data_de_nascimento')),
                    Forms\Components\Select::make('nacionalidade')
                    ->relationship('pais', 'official_name')
                    ->label(__('funcionario.País de Origem')),
                    Forms\Components\FileUpload::make('foto')->image()->label(__('funcionario.foto')),
                    ]),
                    Fieldset::make('Dados Profissionais')->label(__('funcionario.Dados Profissionais'))->schema([
                        Forms\Components\Select::make('tipo_de_contrato_id')
                            ->relationship('tipo_de_contrato','nome')
                            ->label(__('funcionario.Tipo de Contrato'))
                            ->required()
                            ->createOptionForm([
                                Section::make()->schema([
                                    Forms\Components\TextInput::make('nome')
                                        ->required()
                                        ->maxLength(255)
                                        ->label(__('funcionario.nome')),
                                    Forms\Components\Textarea::make('descricao')
                                        ->maxLength(65535)
                                        ->columnSpanFull()
                                        ->label(__('funcionario.descricao')),
                                ])
                            ]),
                        Forms\Components\DatePicker::make('data_de_contrato')
                            ->required()
                            ->label(__('funcionario.data_de_contrato')),
                            Forms\Components\TextInput::make('redimento_salarial')
                            ->required()
                            ->numeric()
                            ->label(__('funcionario.redimento_salarial')),
                    ]),
                    Fieldset::make('Contactos')->label(__('funcionario.Contactos'))->schema([
                        Forms\Components\TextInput::make('numero_de_telemovel')
                        ->tel()
                        ->required()
                        ->maxLength(255)
                        ->label(__('funcionario.numero_de_telemovel')),
                    Forms\Components\TextInput::make('email')
                        ->email()
                        ->required()
                        ->maxLength(255)
                        ->label(__('funcionario.email')),
                    Forms\Components\TextInput::make('morada')
                        ->required()
                        ->maxLength(255)
                        ->label(__('funcionario.morada')),
                    Forms\Components\TextInput::make('codigo_postal')
                        ->required()
                        ->maxLength(255)
                        ->label(__('funcionario.codigo_postal')),
                    ]),
                Fieldset::make()->schema([
                    TextInput::make('password')
                    ->password()
                    ->required()
                    ->minLength(8)
                    ->maxLength(255)
                    ->label(__('funcionario.password')),
                ])
                ])
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('tipo_de_contrato.nome')
                    ->sortable()
                    ->label(__('funcionario.tipo_de_contrato.nome')),
                Tables\Columns\TextColumn::make('nome')
                    ->searchable()
                    ->label(__('funcionario.nome')),
                Tables\Columns\TextColumn::make('nome_completo')
                    ->searchable()
                    ->label(__('funcionario.nome_completo')),
                Tables\Columns\TextColumn::make('data_de_nascimento')
                    ->date()
                    ->sortable()
                    ->label(__('funcionario.data_de_nascimento')),
                Tables\Columns\TextColumn::make('pais.official_name')
                    ->label(__('funcionario.official_name')),
                Tables\Columns\TextColumn::make('data_de_contrato')
                    ->date()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->label(__('funcionario.data_de_contrato')),
                Tables\Columns\ImageColumn::make('foto')
                    ->circular()
                    ->label(__('funcionario.foto')),
                Tables\Columns\TextColumn::make('numero_de_telemovel')
                    ->searchable()
                    ->label(__('funcionario.numero_de_telemovel')),
                Tables\Columns\TextColumn::make('email')
                    ->searchable()
                    ->label(__('funcionario.email')),
                Tables\Columns\TextColumn::make('morada')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->label(__('funcionario.morada')),
                Tables\Columns\TextColumn::make('codigo_postal')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->label(__('funcionario.codigo_postal')),
                Tables\Columns\TextColumn::make('redimento_salarial')
                    ->numeric()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->label(__('funcionario.redimento_salarial')),
                Tables\Columns\TextColumn::make('deleted_at')
                    ->dateTime()
                    ->sortable()
                    ->icon('heroicon-o-trash')
                    ->iconPosition(IconPosition::After)
                    ->iconColor('danger')
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->label(__('funcionario.deleted_at')),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->label(__('funcionario.criado_em')),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->label(__('funcionario.atualizado_em')),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
                Tables\Actions\Action::make('pdf') 
                ->label('PDF')
                ->color('success')
                ->icon('heroicon-o-document')
                ->action(function (Funcionario $record) {
                    return response()->streamDownload(function () use ($record) {
                        echo Pdf::loadHtml(
                            Blade::render('pdf', ['record' => $record])
                        )->stream();
                    }, $record->nome . '.pdf');}),
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
            'index' => Pages\ListFuncionarios::route('/'),
            'create' => Pages\CreateFuncionario::route('/create'),
            'edit' => Pages\EditFuncionario::route('/{record}/edit'),
            'view' => Pages\ViewFuncionario::route('/{record}'),
        ];
    }
}
