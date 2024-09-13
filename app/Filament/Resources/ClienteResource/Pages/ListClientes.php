<?php

namespace App\Filament\Resources\ClienteResource\Pages;

use App\Filament\Resources\ClienteResource;
use App\Models\Cliente;
use Filament\Actions;
use Filament\Resources\Components\Tab;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Database\Eloquent\Builder;

class ListClientes extends ListRecords
{
    protected static string $resource = ClienteResource::class;

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
            ->badge(Cliente::query()->where('deleted_at', null)->count()),
            'arquivado' => Tab::make(__('ui.arquivo'))
            ->modifyQueryUsing(function ($query) {
                return $query->onlyTrashed();
            })
            ->badge(Cliente::onlyTrashed()->count()),
        ];
    }
}
