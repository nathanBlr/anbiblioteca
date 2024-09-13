<?php

namespace App\Filament\Resources\TipoDeContratoResource\Pages;

use App\Filament\Resources\TipoDeContratoResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateTipoDeContrato extends CreateRecord
{
    protected static string $resource = TipoDeContratoResource::class;
    protected function getRedirectUrl(): string
{
    return $this->getResource()::getUrl('index');
}
}
