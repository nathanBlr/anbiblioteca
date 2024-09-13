<?php

namespace App\Filament\Resources\AluguelResource\Pages;

use App\Filament\Resources\AluguelResource;
use App\Models\Aluguel;
use Filament\Pages\Actions;
use Filament\Resources\Pages\CreateRecord;
use Filament\Resources\Pages\EditRecord;

class CreateAluguel extends CreateRecord
{
    protected static string $resource = AluguelResource::class;

    protected function getRedirectUrl(): string
{
    return $this->getResource()::getUrl('index');
}
}


