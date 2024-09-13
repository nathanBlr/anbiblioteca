<?php

namespace App\Filament\Resources\FuncionarioResource\Pages;

use App\Filament\Resources\FuncionarioResource;
use Filament\Actions;
use Filament\Actions\RestoreAction;
use Filament\Resources\Pages\EditRecord;

class EditFuncionario extends EditRecord
{
    protected static string $resource = FuncionarioResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
            RestoreAction::make(),
        ];
    }
}
