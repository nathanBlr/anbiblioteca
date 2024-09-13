<?php

namespace App\Filament\Widgets;

use App\Models\Aluguel;
use Filament\Tables;
use Filament\Tables\Columns\BooleanColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;

class UltimoAlugadosTable extends BaseWidget
{
    protected function getTableHeading(): ?string
    {
        return __('ui.ultimosLivros');
    }

    protected static ?int $sort = 3;
    
    public function table(Table $table): Table
    {
        return $table
            ->query(Aluguel::query()->latest()->limit(10))
            ->columns([
                TextColumn::make('copia.identificador')
                    ->label(__('livro.Livros')),
                TextColumn::make('cliente.nome')
                    ->label(__('cliente.Clientes')),
                BooleanColumn::make('entregue')
                    ->label(__('aluguel.entregue')),
            ]);
    }
}
