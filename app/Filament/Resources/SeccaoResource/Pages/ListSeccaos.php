<?php

namespace App\Filament\Resources\SeccaoResource\Pages;

use App\Filament\Resources\SeccaoResource;
use App\Models\Seccao;
use Filament\Actions;
use Filament\Resources\Components\Tab;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Database\Eloquent\Builder;

class ListSeccaos extends ListRecords
{
    protected static string $resource = SeccaoResource::class;

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
            ->badge(Seccao::query()->where('deleted_at', null)->count()),
            'arquivado' => Tab::make(__('ui.arquivo'))
            ->modifyQueryUsing(function ($query) {
                return $query->onlyTrashed();
            })
            ->badge(Seccao::onlyTrashed()->count()),
        ];
    }
}
