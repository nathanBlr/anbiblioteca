<?php

namespace App\Filament\Widgets;

use App\Models\Aluguel;
use Filament\Tables;
use Filament\Tables\Columns\ColorColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;

class LivrosAtrasadosTable extends BaseWidget
{
    public function getTableHeading(): ?string
    {
        return __('ui.livroAtraso');
    }

    protected static ?int $sort = 2;

    public function table(Table $table): Table
    {
        return $table
            ->query(Aluguel::query()->where('entregue', 0)->where('estado', 'Em Atraso'))
            ->columns([
                TextColumn::make('copia.identificador')
                    ->label(__('livro.Livros')),
                TextColumn::make('cliente.nome')
                    ->label(__('cliente.Clientes')),
            ]);
    }
}
