<?php

namespace App\Filament\Resources\FuncionarioResource\Pages;

use App\Filament\Resources\FuncionarioResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewFuncionario extends ViewRecord
{
    protected static string $resource = FuncionarioResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }
}
