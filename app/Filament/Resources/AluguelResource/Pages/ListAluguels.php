<?php

namespace App\Filament\Resources\AluguelResource\Pages;

use App\Filament\Resources\AluguelResource;
use App\Models\Aluguel;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Filament\Pages\Actions\ButtonAction;
use Filament\Resources\Components\Tab;
use Illuminate\Database\Eloquent\Builder;

class ListAlugueis extends ListRecords
{
    protected static string $resource = AluguelResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }

    public function getTabs(): array
    {
        return [
            'ativado' => Tab::make(__('ui.ativo'))
            ->modifyQueryUsing(fn (Builder $query) => $query->where('deleted_at', null))
            ->badge(Aluguel::query()->where('deleted_at', null)->count()),
            'arquivado' => Tab::make(__('ui.arquivo'))
            ->modifyQueryUsing(function ($query) {
                return $query->onlyTrashed();
            })
            ->badge(Aluguel::onlyTrashed()->count()),
        ];
    }
}

