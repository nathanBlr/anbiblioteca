<?php

namespace App\Filament\Resources\GeneroResource\Pages;

use App\Filament\Resources\GeneroResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewGenero extends ViewRecord
{
    protected static string $resource = GeneroResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }
}
