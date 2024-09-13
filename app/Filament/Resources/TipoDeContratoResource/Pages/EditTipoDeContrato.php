<?php

namespace App\Filament\Resources\TipoDeContratoResource\Pages;

use App\Filament\Resources\TipoDeContratoResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditTipoDeContrato extends EditRecord
{
    protected static string $resource = TipoDeContratoResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
