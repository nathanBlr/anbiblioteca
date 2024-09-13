<?php

namespace App\Filament\Resources\FuncionarioResource\Pages;

use App\Filament\Resources\FuncionarioResource;
use App\Models\Funcionario;
use Filament\Actions;
use Filament\Resources\Components\Tab;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Database\Eloquent\Builder;

class ListFuncionarios extends ListRecords
{
    protected static string $resource = FuncionarioResource::class;
    //protected static ?string $getTitle = __("funcionario.Funcionário");
    
    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()->label(__('funcionario.novo_funcionario'))
        ];
    }

    public function getTabs(): array
    {
        return [
            'ativado' => Tab::make(__('ui.ativo'))
            ->modifyQueryUsing(fn (Builder $query) => $query->where('deleted_at', null))
            ->badge(Funcionario::query()->where('deleted_at', null)->count()),
            'arquivado' => Tab::make(__('ui.arquivo'))
            ->modifyQueryUsing(function ($query) {
                return $query->onlyTrashed();
            })
            ->badge(Funcionario::onlyTrashed()->count()),
        ];
    }
}
