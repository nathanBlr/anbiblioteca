<?php

namespace App\Filament\Resources\SeccaoResource\Pages;

use App\Filament\Resources\SeccaoResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewSeccao extends ViewRecord
{
    protected static string $resource = SeccaoResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }
}
