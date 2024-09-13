<?php

namespace App\Filament\Resources\GeneroResource\Pages;

use App\Filament\Resources\GeneroResource;
use App\Models\Genero;
use Filament\Actions;
use Filament\Resources\Components\Tab;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Database\Eloquent\Builder;

class ListGeneros extends ListRecords
{
    protected static string $resource = GeneroResource::class;

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
            ->badge(Genero::query()->where('deleted_at', null)->count()),
            'arquivado' => Tab::make(__('ui.arquivo'))
            ->modifyQueryUsing(function ($query) {
                return $query->onlyTrashed();
            })
            ->badge(Genero::onlyTrashed()->count()),
        ];
    }
}
