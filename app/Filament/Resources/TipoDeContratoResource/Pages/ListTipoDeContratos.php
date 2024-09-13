<?php

namespace App\Filament\Resources\TipoDeContratoResource\Pages;

use App\Filament\Resources\TipoDeContratoResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListTipoDeContratos extends ListRecords
{
    protected static string $resource = TipoDeContratoResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
