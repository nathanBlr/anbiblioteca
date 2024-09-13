<?php

namespace App\Filament\Resources\AluguelResource\Pages;

use App\Filament\Resources\AluguelResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewAluguel extends ViewRecord
{
    protected static string $resource = AluguelResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }
}
