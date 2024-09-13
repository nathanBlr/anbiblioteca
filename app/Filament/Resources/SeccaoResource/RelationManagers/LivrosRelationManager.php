<?php

namespace App\Filament\Resources\SeccaoResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Components\Actions\Action;
use Filament\Forms\Components\Fieldset;
use Filament\Forms\Components\MarkdownEditor;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Wizard;
use Filament\Forms\Components\Wizard\Step;
use Filament\Forms\Form;
use Filament\Infolists\Components\Tabs;
use Filament\Infolists\Components\Fieldset as InfolistFieldset;
use Filament\Infolists\Components\RepeatableEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Infolist;
use Filament\Resources\RelationManagers\RelationManager;
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

class LivrosRelationManager extends RelationManager
{
    protected static string $relationship = 'livros';

    protected static ?string $title = 'Obras';

    public function form(Form $form): Form
    {
        return $form
        ->schema([
            Wizard::make([
                Step::make(__('livro.Informação sobre a Obra'))
                    ->icon('heroicon-s-information-circle')
                    ->schema([
                                Forms\Components\TextInput::make('titulo')
                                    ->label(__('livro.Titulo da Obra'))
                                    ->required()
                                    ->maxLength(255),
                                Forms\Components\TextInput::make('numero_de_paginas')
                                    ->label(__('livro.numero_de_paginas'))
                                    ->required()
                                    ->numeric(),
                                Forms\Components\DatePicker::make('data_de_publicacao')
                                    ->label(__('livro.ano_de_publicacao'))
                                    ->native(false)
                                    ->required(),
                                Select::make('classificacao')
                                    ->label(__('livro.classeficacao'))
                                    ->required()
                                    ->native(false)
                                    ->options([
                                        'livre' => 'Livre',
                                        'juvenil' => 'Juvenil(+12)',
                                        'maduro' => 'Maduro(+16)',
                                        'Adulto' => 'Adulto(+18)'
                                    ]),
                                Select::make('autor_id')
                                    ->relationship('autors', 'nome_artistico')
                                    ->label(__('livro.autor_id'))
                                    ->searchable()
                                    ->preload()
                                    ->multiple()
                                    ->required()
                                    ->createOptionForm([
                                        Fieldset::make(__('livro.Informação sobre o Autor'))
                                            ->schema([
                                                Forms\Components\TextInput::make('nome_completo')
                                                    ->required()
                                                    ->maxLength(255)
                                                    ->label(__('livro.nome_completo')),
                                                Forms\Components\TextInput::make('nome_artistico')
                                                    ->label(__('livro.nome_artistico'))
                                                    ->required()
                                                    ->maxLength(255),
                                                Forms\Components\DatePicker::make('data_de_nascimento')
                                                    ->required()
                                                    ->native(false)
                                                    ->label(__('livro.data_de_nascimento')),
                                                Forms\Components\Select::make('nacionalidade')
                                                    ->relationship('pais', 'official_name')
                                                    ->label(__('livro.País de Origem'))
                                                    ->searchable()
                                                    ->preload(),
                                            ])->columns(2)
                                    ]),
                                Select::make('gerero_id')
                                    ->relationship('generos', 'nome')
                                    ->label(__('livro.genero_id'))
                                    ->searchable()
                                    ->preload()
                                    ->multiple()
                                    ->required()
                                    ->createOptionForm([
                                        Section::make(__('livro.Infromação sobre o Genero Literario'))
                                            ->schema([
                                                Forms\Components\TextInput::make('nome')
                                                    ->required()
                                                    ->maxLength(255)
                                                    ->label(__('livro.nome')),
                                                Forms\Components\TextInput::make('descricao')
                                                    ->label('Descrição')
                                                    ->maxLength(255)
                                                    ->label(__('livro.descricao')),
                                            ])
                                    ]),

                                MarkdownEditor::make('sinopse')
                                    ->toolbarButtons([
                                        'bold',
                                        'italic',
                                        'redo',
                                        'undo',
                                    ])
                                    ->columnSpanFull()
                                    ->label(__('livro.sinopse')),

                    ])->columns(2),

                Step::make(__('livro.copias'))
                    ->icon('heroicon-o-list-bullet')
                    ->schema([
                        Repeater::make('copias')
                            ->label('')
                            ->relationship()
                            ->schema([
                                TextInput::make('id')
                                    ->disabled()
                                    ->hidden()
                                    ->label(__('livro.id')),
                                TextInput::make('livro_id')
                                    ->disabled()
                                    ->hidden()
                                    ->label(__('livro.livro_id')),
                                Forms\Components\TextInput::make('numero_de_edicao')
                                    ->required()
                                    ->numeric()
                                    ->label(__('livro.numero_de_edicao')),
                                Forms\Components\DatePicker::make('data_de_edicao')
                                    ->required()
                                    ->label(__('livro.ano_de_edicao')),
                                Forms\Components\TextInput::make('editora')
                                    ->required()
                                    ->maxLength(255)
                                    ->label(__('livro.editora')),
                            ])
                            ->columns(3)
                            ->cloneable()
                            ->addActionLabel(__('livro.adicionar_copia'))
                            ->deleteAction(fn (Action $action) => $action->requiresConfirmation(),)
                            ->itemLabel(fn (array $state): ?string => 'COD' . $state['livro_id'] . '_' . $state['id'] ?? null),
                    ])
            ])->columnSpanFull()
        ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('titulo')
            ->columns([
                Tables\Columns\TextColumn::make('titulo')
                    ->searchable()
                    ->label(__('livro.titulo')),
                TextColumn::make('autors.nome_artistico')
                    ->label(__('livro.Autor')),
                TextColumn::make('generos.nome')
                    ->badge()
                    ->label(__('livro.generos.nome')),
                TextColumn::make('classificacao')
                    ->badge()
                    ->label(__('livro.classeficacao'))
                    ->color(fn (string $state): string => match ($state) {
                        'livre' => 'success',
                        'juvenil' => 'info',
                        'maduro' => 'warning',
                        'adulto' => 'danger',
                    }),
                Tables\Columns\TextColumn::make('numero_de_paginas')
                    ->numeric()
                    ->toggleable()
                    ->label(__('livro.numero_de_paginas')),
                Tables\Columns\TextColumn::make('data_de_publicacao')
                    ->date('d/m/Y')
                    ->sortable()
                    ->icon('heroicon-o-calendar-days')
                    ->iconPosition(IconPosition::After)
                    ->iconColor('primary')
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->label(__('livro.ano_de_publicacao')),
                Tables\Columns\TextColumn::make('copias_count')
                    ->label(__('livro.Numero de Cópias'))
                    ->badge()
                    ->color('info')
                    ->counts('copias')
                    ->sortable(),
                Tables\Columns\TextColumn::make('deleted_at')
                    ->dateTime()
                    ->sortable()
                    ->icon('heroicon-o-trash')
                    ->iconPosition(IconPosition::After)
                    ->iconColor('danger')
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->label(__('livro.deleted_at')),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->icon('heroicon-o-clock')
                    ->iconPosition(IconPosition::After)
                    ->iconColor('primary')
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->label(__('livro.created_at')),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->icon('heroicon-o-clock')
                    ->iconPosition(IconPosition::After)
                    ->iconColor('primary')
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->label(__('livro.updated_at')),
            ])
            ->filters([
                SelectFilter::make('autor_id')
                    ->relationship('autors', 'nome_artistico')
                    ->searchable()
                    ->preload()
                    ->label(__('livro.por_autor')),
                SelectFilter::make('genero_id')
                    ->relationship('generos', 'nome')
                    ->searchable()
                    ->preload()
                    ->label(__('livro.por_genero')),
                SelectFilter::make('classificacao')
                    ->preload()
                    ->options([
                        'livre' => 'Livre',
                        'juvenil' => 'Juvenil(+12)',
                        'maduro' => 'Maduro(+16)',
                        'Adulto' => 'Adulto(+18)'
                    ])
                    ->native(false)
                    ->label(__('livro.por_classificacao')),
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make(),
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

    public function infolist(Infolist $infolist): Infolist
    {
        return $infolist
        ->schema([
            Tabs::make('Tabs')
                ->tabs([
                    
                    Tabs\Tab::make(__('livro.Informação sobre a Obra'))
                    ->icon('heroicon-o-information-circle')
                        ->schema([
                                    TextEntry::make('titulo')
                                        ->label(__('livro.Titulo da Obra')),
                                    TextEntry::make('autors.nome_artistico')
                                        ->label(__('livro.autor_id')),
                                    TextEntry::make('numero_de_paginas')
                                        ->label(__('livro.numero_de_paginas')),
                                    TextEntry::make('data_de_publicacao')
                                        ->label(__('livro.ano_de_publicacao'))
                                        ->date('d/m/Y')
                                        ->icon('heroicon-o-calendar-days')
                                        ->iconPosition(IconPosition::After)
                                        ->iconColor('primary'),
                                    TextEntry::make('generos.nome')
                                        ->label(__('livro.genero_id'))
                                        ->badge(),
                                    TextEntry::make('classificacao')
                                        ->label(__('livro.classeficacao'))
                                        ->badge()
                                        ->color(fn (string $state): string => match ($state) {
                                            'livre' => 'success',
                                            'juvenil' => 'info',
                                            'maduro' => 'warning',
                                            'adulto' => 'danger',
                                        }),
                                    TextEntry::make('sinopse')
                                        ->placeholder(__('livro.sinopse_null'))
                                        ->columnSpanFull()
                                        ->label(__('livro.sinopse')),
                        ])->columns(2),

                    Tabs\Tab::make(__('livro.copias'))
                        ->icon('heroicon-o-list-bullet')
                        ->schema([
                            RepeatableEntry::make('copias')
                                ->label('')
                                ->schema([
                                    TextEntry::make('identificador')
                                        ->columnSpanFull()
                                        ->label(__('livro.identificador')),
                                    InfolistFieldset::make(__('livro.edicao'))
                                        ->schema([
                                            TextEntry::make('numero_de_edicao')
                                                ->label(__('livro.numero_de_edicao'))
                                                ->suffix('ª'),
                                            TextEntry::make('data_de_edicao')
                                                ->label(__('livro.ano_de_edicao'))
                                                ->date('d/m/Y')
                                                ->icon('heroicon-o-calendar-days')
                                                ->iconPosition(IconPosition::After)
                                                ->iconColor('primary'),
                                            TextEntry::make('editora')
                                            ->label(__('livro.editora')),
                                        ])->columns(3)
                                ])

                        ]),
                ])->columnSpanFull()
            
        ]);
    }
}
